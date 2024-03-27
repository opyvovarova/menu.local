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

        echo '<ul>';
        foreach ($menuItems as $menuItem)
        {
            renderMenu($menuItem);
        }

        echo '</ul>';


        function renderMenu($menuItem)
        {
            echo '<li>' . str_repeat('&nbsp;&nbsp;', $menuItem['depth']) . $menuItem['name'] . '</li>';

            // Проверяем, есть ли у категории дочерние элементы
            if (!empty($menuItem['children'])) {
                echo '<ul>';
                foreach ($menuItem['children'] as $child) {
                    renderMenu($child);
                }
                echo '</ul>';
            }
        }

    ?>
</ul>
</body>
</html>