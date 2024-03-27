<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../lib/db_connection.php';

class MenuImporter {
    private $pdo;

    public function __construct()
    {
        global $conn;
        $this->pdo = $conn;
    }

    public function importFromJSON($data, $parentId = null)
    {

        foreach ($data as $item)
        {
            // Вставляем текущую категорию в таблицу categories
            $stmt = $this->pdo->prepare("INSERT INTO categories (name, alias, parent_id) VALUES (:name, :alias, :parent_id)");
            $stmt->execute([
               ':name'      => $item['name'],
               ':alias'     => $item['alias'],
               ':parent_id' => $parentId
            ]);

            // получаем Id вставленной записи
            $categoryId = $this->pdo->lastInsertId();

            // Проверяем наличие дочерних категорий и рекурсивно вызываем этот же метод
            if (isset($item['childrens']) && !empty($item['childrens']))
            {
                $this->importFromJSON($item['childrens'], $categoryId);
            }

            //После того как все категории добавлены нужно выполнить вставку связей в таблицу categories_relations
            foreach ($data as $item)
            {
                if (isset($item['childrens']) && !empty($item['childrens']))
                {
                    $categoryId = $this->getCategoryIdByName($item['name']);
                    $this->insertCategoryRelations($item['childrens'], $categoryId);
                }
            }
        }
    }
    //метод получения id категории по ее имени
    private function getCategoryIdByName($name)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE name = :name");
        $stmt->execute([
            ':name' => $name
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return  $result['id'] ?? null;
    }

    // Метод для вставки связей в таблицу categories_relations
    private function insertCategoryRelations($childrens, $parentId)
    {
        foreach ($childrens as $child)
        {
            $childId = $this->getCategoryIdByName($child['name']);
            if ($childId) {
                $stmt = $this->pdo->prepare("INSERT INTO category_relations (parent_id, child_id) VALUES  (:parent_id, :child_id)");
                $stmt->execute([
                    ':parent_id' => $parentId,
                    ':child_id'  => $childId
                ]);
            }
        }
    }

}

$importer = new MenuImporter;
$filename = __DIR__ . '\..\data\categories.json';
$json = file_get_contents($filename);

if ($json === false) {
    throw new Exception("Ошибка чтение файла $filename");
}

//преобразуем JSON  в массив PHP
$data = json_decode($json, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Ошибка при парсинге JSON: ' . json_last_error_msg());
}

$importer->importFromJSON($data);
echo "Импорт данных из JSON файла успешно завершен.";

