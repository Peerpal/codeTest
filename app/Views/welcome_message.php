<?= $this->extend('default') ?>
<?= $this->section('content') ?>

<div class="menus">
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