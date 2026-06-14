<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    private ProductModel $productModel;
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categoryId = $this->request->getGet('category_id');
        $keyword = trim((string) $this->request->getGet('q'));

        $builder = $this->productModel
            ->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id', 'left');

        if ($categoryId !== null && $categoryId !== '') {
            $builder->where('products.category_id', (int) $categoryId);
        }

        if ($keyword !== '') {
            $builder->like('products.name', $keyword);
        }

        $products = $builder
            ->orderBy('products.created_at', 'DESC')
            ->paginate(10, 'products');

        return view('product/index', [
            'title'      => 'Produk',
            'products'   => $products,
            'pager'      => $this->productModel->pager,
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
            'filters'    => [
                'category_id' => $categoryId,
                'q'           => $keyword,
            ],
        ]);
    }

    public function create()
    {
        return view('product/create', [
            'title'      => 'Tambah Produk',
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        if (! $this->validate($this->validationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $imageName = $this->uploadImage();

        $this->productModel->insert([
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => trim((string) $this->request->getPost('name')),
            'price'       => (float) $this->request->getPost('price'),
            'stock'       => (int) $this->request->getPost('stock'),
            'image'       => $imageName,
            'is_active'   => (int) $this->request->getPost('is_active'),
        ]);

        return redirect()->to('/product')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = $this->productModel->find((int) $id);

        if (! $product) {
            return redirect()->to('/product')->with('error', 'Produk tidak ditemukan.');
        }

        return view('product/edit', [
            'title'      => 'Edit Produk',
            'product'    => $product,
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function update($id)
    {
        $product = $this->productModel->find((int) $id);

        if (! $product) {
            return redirect()->to('/product')->with('error', 'Produk tidak ditemukan.');
        }

        if (! $this->validate($this->validationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $imageName = $this->uploadImage();
        $data = [
            'category_id' => (int) $this->request->getPost('category_id'),
            'name'        => trim((string) $this->request->getPost('name')),
            'price'       => (float) $this->request->getPost('price'),
            'stock'       => (int) $this->request->getPost('stock'),
            'is_active'   => (int) $this->request->getPost('is_active'),
        ];

        if ($imageName !== null) {
            $data['image'] = $imageName;
            $this->deleteImage($product->image);
        }

        $this->productModel->update($product->id, $data);

        return redirect()->to('/product')->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete($id)
    {
        $product = $this->productModel->find((int) $id);

        if (! $product) {
            return redirect()->to('/product')->with('error', 'Produk tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $itemCount = $db->table('transaction_items')->where('product_id', $product->id)->countAllResults();

        if ($itemCount > 0) {
            return redirect()->to('/product')
                ->with('error', 'Produk tidak dapat dihapus karena sudah pernah masuk transaksi.');
        }

        $this->productModel->delete($product->id);
        $this->deleteImage($product->image);

        return redirect()->to('/product')->with('success', 'Produk berhasil dihapus.');
    }

    public function toggle($id)
    {
        $product = $this->productModel->find((int) $id);

        if (! $product) {
            return redirect()->to('/product')->with('error', 'Produk tidak ditemukan.');
        }

        $this->productModel->update($product->id, [
            'is_active' => (int) $product->is_active === 1 ? 0 : 1,
        ]);

        return redirect()->to('/product')->with('success', 'Status produk berhasil diperbarui.');
    }

    private function validationRules(): array
    {
        return [
            'name' => [
                'label'  => 'Nama Produk',
                'rules'  => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama produk wajib diisi.',
                    'min_length' => 'Nama produk minimal 2 karakter.',
                    'max_length' => 'Nama produk maksimal 100 karakter.',
                ],
            ],
            'category_id' => [
                'label'  => 'Kategori',
                'rules'  => 'required|is_not_unique[categories.id]',
                'errors' => [
                    'required'      => 'Kategori wajib dipilih.',
                    'is_not_unique' => 'Kategori yang dipilih tidak valid.',
                ],
            ],
            'price' => [
                'label'  => 'Harga',
                'rules'  => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required'              => 'Harga wajib diisi.',
                    'numeric'               => 'Harga harus berupa angka.',
                    'greater_than_equal_to' => 'Harga tidak boleh negatif.',
                ],
            ],
            'stock' => [
                'label'  => 'Stok',
                'rules'  => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required'              => 'Stok wajib diisi.',
                    'integer'               => 'Stok harus berupa bilangan bulat.',
                    'greater_than_equal_to' => 'Stok tidak boleh negatif.',
                ],
            ],
            'is_active' => [
                'label'  => 'Status',
                'rules'  => 'required|in_list[0,1]',
                'errors' => [
                    'required' => 'Status wajib dipilih.',
                    'in_list'  => 'Status yang dipilih tidak valid.',
                ],
            ],
            'image' => [
                'label'  => 'Gambar',
                'rules'  => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,1024]',
                'errors' => [
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in'  => 'Format gambar harus JPG, PNG, atau WEBP.',
                    'max_size' => 'Ukuran gambar maksimal 1MB.',
                ],
            ],
        ];
    }

    private function uploadImage(): ?string
    {
        $image = $this->request->getFile('image');

        if (! $image || ! $image->isValid() || $image->hasMoved()) {
            return null;
        }

        $targetPath = ROOTPATH . 'public/assets/img/products';
        if (! is_dir($targetPath)) {
            mkdir($targetPath, 0775, true);
        }

        $newName = $image->getRandomName();
        $image->move($targetPath, $newName);

        return $newName;
    }

    private function deleteImage(?string $imageName): void
    {
        if (! $imageName) {
            return;
        }

        $path = ROOTPATH . 'public/assets/img/products/' . $imageName;

        if (is_file($path)) {
            unlink($path);
        }
    }
}
