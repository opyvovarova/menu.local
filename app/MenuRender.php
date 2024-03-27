<?php

require_once  __DIR__ . '/../vendor/autoload.php';
require_once  __DIR__ . '/../lib/db_connection.php';
require_once  __DIR__ . '/../services/CategoryService.php';

class MenuRender
{

    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getMenuItems()
    {
        return $this->categoryService->getAllCategories();
    }
}