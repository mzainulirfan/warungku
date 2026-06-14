<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>Edit Produk</h2>
        <p>Perbarui data produk yang sudah terdaftar.</p>
    </div>
    <a class="btn btn-outline" href="<?= site_url('product') ?>">Kembali</a>
</section>

<?= view('product/form', [
    'action'     => site_url('product/update/' . $product->id),
    'product'    => $product,
    'categories' => $categories,
    'submitText' => 'Simpan Perubahan',
]) ?>
<?= $this->endSection() ?>
