<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../lib/db_connection.php';

class CategoryService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    //Получение всех категорий верхнего уровня
    public function getRootCategories()
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM categories WHERE parent_id IS NULL
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Получения всех категорий из базы данных в виде иерархической структуры
    public function getAllCategories()
    {
        $stmt = $this->pdo->prepare("
            SELECT id, name, parent_id, 0 AS depth
            FROM categories
            WHERE parent_id IS NULL
            ORDER BY id
        ");

        $stmt->execute();
        $rootCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allCategories = [];
        foreach ($rootCategories as $rootCategory)
        {
            $childCategories = $this->getChildCategories($rootCategory['id'], 1);
            $allCategories = array_merge($allCategories, [$rootCategory], $childCategories);
        }

        return $allCategories;

    }

    //Для Получения всез дочерних категорий для родителя
    private function getChildCategories($parentId, $depth)
    {
        $stmt = $this->pdo->prepare("
            SELECT id, name, parent_id, :depth AS depth
            FROM categories
            WHERE parent_id = :parentId
            ORDER BY id
        ");
        $stmt->bindValue(':depth', $depth, PDO::PARAM_INT);
        $stmt->bindValue(':parentId', $parentId, PDO::PARAM_INT);
        $stmt->execute();
        $childCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $allChildCategories = [];

        foreach($childCategories as $childCategory)
        {
            $grandChildCategories = $this->getChildCategories($childCategory['id'], $depth + 1);
            $allChildCategories = array_merge($allChildCategories, [$childCategory], $grandChildCategories);
        }

        return $allChildCategories;

    }

}