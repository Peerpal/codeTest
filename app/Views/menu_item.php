<li>
    <p onclick="PopUp('<?=  esc($child['label']) ?>', '<?=  esc($child['id']) ?>')"><?= esc($child['label']) ?></p>
    <?php if (!empty($child['children'])): ?>
        <ul>
            <?php foreach ($child['children'] as $child): ?>
                <?php include('menu_item.php') ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</li>
