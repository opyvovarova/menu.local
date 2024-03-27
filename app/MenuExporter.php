<?php

require_once  __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../lib/db_connection.php';
require_once __DIR__ . '/../services/CategoryService.php';
class CategoryExporter
{
    private $pdo;
    private $categoryService;

    public function __construct(PDO $pdo, CategoryService $categoryService)
    {
       $this->pdo = $pdo;
       $this->categoryService = $categoryService;
    }

    public function exportRootCategoriesToText($filename)
    {
        $rootCategories = $this->categoryService->getRootCategories();

        //Открываем файл для записи
        $file = fopen($filename, 'w');

        //Записываем категории первого уровня вложенности в файл
        foreach ($rootCategories as $category)
        {
            fwrite($file, $category['name'] . PHP_EOL);
        }

        fclose($file);
    }

    public function exportAllCategoriesToText($filename)
    {
        $categories = $this->categoryService->getAllCategories();

        $file = fopen($filename, 'w');

        foreach ($categories as $category)
        {
            $indentation = str_repeat(' ', $category['depth'] * 2);
            fwrite($file, $indentation . $category['name'] . PHP_EOL);
        }

        fclose($file);
    }

}

global $conn;
$categoryService = new CategoryService($conn);
$categoryExporter = new CategoryExporter($conn, $categoryService);

$rootExportFileName = __DIR__ . '/../data/type_b.txt';
$allExportFileName =  __DIR__ . '/../data/type_a.txt';

try {
    $categoryExporter->exportRootCategoriesToText($rootExportFileName);
    echo "Категории первого уровня вложенности успешно экспортированы в файл $rootExportFileName";

    $categoryExporter->exportAllCategoriesToText($allExportFileName);
    echo "Все категории успешно экспортированы в файл $allExportFileName";
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
