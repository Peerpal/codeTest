<p>
    <?= esc($child['label']) ?>
</p>
<?php foreach ($child['children'] as $child): ?>
    <?= $this->include('menu_item') ?>
<?php endforeach ?>