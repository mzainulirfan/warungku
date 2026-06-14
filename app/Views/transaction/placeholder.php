<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($heading) ?></h3>
    </div>
    <div class="card-body">
        <p class="muted-text"><?= esc($message) ?></p>
    </div>
</section>
<?= $this->endSection() ?>
