<ul>
    <?php foreach ($menuItems as $menuItem): ?>
        <li><?php echo str_repeat('&nbsp;&nbsp;', $menuItem['depth']) . $menuItem['name']; ?></li>
        <?php if (!empty($menuItem['children'])): ?>
            <ul>
                <?php foreach ($menuItem['children'] as $child): ?>
                    <li><?php echo str_repeat('&nbsp;&nbsp;', $child['depth']) . $child['name']; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>