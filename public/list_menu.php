 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Menu</title>
    <link rel="stylesheet" href="style.css">
</head>
    <body> 
    
        <?php
            require_once __DIR__ . '/../vendor/autoload.php';
            require_once __DIR__ . '/../lib/db_connection.php';
            require_once __DIR__ . '/../services/CategoryService.php';
            require_once __DIR__ . '/../app/MenuRender.php';

            global $conn;
            $categoryService = new CategoryService($conn);
            $menuRender = new MenuRender($categoryService);

            //получаем список из меню
            $menuItems = $menuRender->getMenuItems();

            // Подключаем шаблон и передаем ему данные
            include __DIR__ . '/../app/menu_template.php';

        ?>
    </body>
</html> 