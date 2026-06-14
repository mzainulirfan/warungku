<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>Tambah Produk</h2>
        <p>Lengkapi data produk yang akan dijual di halaman kasir.</p>
    </div>
    <a class="btn btn-outline" href="<?= site_url('product') ?>">Kembali</a>
</section>

<?= view('product/form', [
    'action'     => site_url('product/store'),
    'product'    => null,
    'categories' => $categories,
    'submitText' => 'Tambah Produk',
]) ?>
<?= $this->endSection() ?>
