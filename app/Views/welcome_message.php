<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<div class="menus">
   <ul>
       <?php foreach ($data as $menu): ?>
        <li>
            <p onclick="PopUp('<?=  esc($menu['label']) ?>', '<?=  esc($menu['id']) ?>')"><?= esc($menu['label']) ?></p>
                <ul>
                <?php foreach ($menu['children'] as $child): ?>
                    <?php  include('menu_item.php') ?>
                <?php endforeach; ?>
            </ul>
        </li>
       <?php endforeach; ?>
   </ul>
</div>


<?= $this->endSection() ?>


<?php function renderList() { ?>
    echo "child"

<?php } ?>


<script>
    const renderList = (data) => {
        console.log(data, )
    }

    renderList("holla")
</script>