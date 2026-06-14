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

    public function template()
    {
        $rows = [
            ['category_name', 'name', 'price', 'stock', 'is_active'],
            ['Makanan', 'Nasi Goreng', '15000', '20', '1'],
            ['Minuman', 'Es Teh Manis', '5000', '30', '1'],
        ];

        $content = "\xEF\xBB\xBFsep=,\r\n";
        foreach ($rows as $row) {
            $content .= $this->csvLine($row);
        }

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="template_import_produk.csv"')
            ->setBody($content);
    }

    public function store()
    {
        if (! $this->validate($this->validationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if ($imageError = $this->validateImageUpload()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['image' => $imageError]);
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

    public function import()
    {
        $file = $this->request->getFile('product_file');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return redirect()->to('/product')
                ->with('errors', ['product_file' => 'File template produk wajib diunggah.']);
        }

        if (! $file->isValid()) {
            return redirect()->to('/product')
                ->with('errors', ['product_file' => 'Upload file produk tidak valid.']);
        }

        $extension = strtolower($file->getClientExtension());
        if ($extension !== 'csv') {
            return redirect()->to('/product')
                ->with('errors', ['product_file' => 'File harus berformat CSV dari template Excel.']);
        }

        if ($file->getSizeByUnit('kb') > 2048) {
            return redirect()->to('/product')
                ->with('errors', ['product_file' => 'Ukuran file maksimal 2MB.']);
        }

        [$rows, $errors] = $this->readProductImportRows($file->getTempName());

        if ($errors !== []) {
            return redirect()->to('/product')->with('errors', $errors);
        }

        if ($rows === []) {
            return redirect()->to('/product')
                ->with('errors', ['product_file' => 'File tidak berisi data produk.']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $categories = $this->categoryMap();
        $imported = 0;

        foreach ($rows as $row) {
            $categoryKey = mb_strtolower($row['category_name']);
            $categoryId = $categories[$categoryKey] ?? null;

            if ($categoryId === null) {
                $categoryId = (int) $this->categoryModel->insert(['name' => $row['category_name']], true);
                $categories[$categoryKey] = $categoryId;
            }

            $this->productModel->insert([
                'category_id' => $categoryId,
                'name'        => $row['name'],
                'price'       => $row['price'],
                'stock'       => $row['stock'],
                'image'       => null,
                'is_active'   => $row['is_active'],
            ]);
            $imported++;
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->to('/product')
                ->with('error', 'Import produk gagal disimpan. Silakan coba lagi.');
        }

        return redirect()->to('/product')
            ->with('success', $imported . ' produk berhasil diimport.');
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

        if ($imageError = $this->validateImageUpload()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['image' => $imageError]);
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

    private function readProductImportRows(string $path): array
    {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return [[], ['product_file' => 'File tidak dapat dibaca.']];
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return [[], ['product_file' => 'File tidak berisi data produk.']];
        }

        $delimiterLine = $firstLine;
        $firstCell = preg_replace('/^\xEF\xBB\xBF/', '', trim($firstLine));
        if (str_starts_with($firstCell, 'sep=')) {
            $delimiterLine = fgets($handle) ?: $firstLine;
            if (str_starts_with($firstCell, 'sep=;')) {
                $delimiterLine = ';;';
            }
        }

        $delimiter = substr_count($delimiterLine, ';') > substr_count($delimiterLine, ',') ? ';' : ',';
        rewind($handle);

        $header = null;
        $rows = [];
        $errors = [];
        $line = 0;

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $line++;
            $data = array_map(static fn ($value) => trim((string) $value), $data);

            if ($data === [] || implode('', $data) === '') {
                continue;
            }

            $firstCell = preg_replace('/^\xEF\xBB\xBF/', '', $data[0] ?? '');
            if ($line === 1 && str_starts_with($firstCell, 'sep=')) {
                continue;
            }

            if ($header === null) {
                $data[0] = $firstCell;
                $header = $data;
                $expected = ['category_name', 'name', 'price', 'stock', 'is_active'];

                if ($header !== $expected) {
                    fclose($handle);
                    return [[], [
                        'product_file' => 'Header file harus: category_name, name, price, stock, is_active.',
                    ]];
                }

                continue;
            }

            $rowNumber = $line;
            $row = array_combine($header, array_pad($data, count($header), ''));
            $rowErrors = $this->validateImportRow($row, $rowNumber);

            if ($rowErrors !== []) {
                $errors = array_merge($errors, $rowErrors);
                if (count($errors) >= 20) {
                    $errors[] = 'Terlalu banyak error. Perbaiki file lalu upload ulang.';
                    break;
                }
                continue;
            }

            $rows[] = [
                'category_name' => $row['category_name'],
                'name'          => $row['name'],
                'price'         => (float) $row['price'],
                'stock'         => (int) $row['stock'],
                'is_active'     => (int) $row['is_active'],
            ];
        }

        fclose($handle);

        if (count($rows) > 500) {
            $errors[] = 'Import maksimal 500 produk dalam satu file.';
        }

        return [$rows, $errors];
    }

    private function validateImportRow(array $row, int $rowNumber): array
    {
        $errors = [];

        if (($row['category_name'] ?? '') === '') {
            $errors[] = 'Baris ' . $rowNumber . ': kategori wajib diisi.';
        } elseif (mb_strlen($row['category_name']) > 50) {
            $errors[] = 'Baris ' . $rowNumber . ': kategori maksimal 50 karakter.';
        }

        if (($row['name'] ?? '') === '') {
            $errors[] = 'Baris ' . $rowNumber . ': nama produk wajib diisi.';
        } elseif (mb_strlen($row['name']) < 2 || mb_strlen($row['name']) > 100) {
            $errors[] = 'Baris ' . $rowNumber . ': nama produk harus 2 sampai 100 karakter.';
        }

        if (($row['price'] ?? '') === '' || ! is_numeric($row['price']) || (float) $row['price'] < 0) {
            $errors[] = 'Baris ' . $rowNumber . ': harga harus angka dan tidak boleh negatif.';
        }

        if (($row['stock'] ?? '') === '' || filter_var($row['stock'], FILTER_VALIDATE_INT) === false || (int) $row['stock'] < 0) {
            $errors[] = 'Baris ' . $rowNumber . ': stok harus bilangan bulat dan tidak boleh negatif.';
        }

        if (! in_array((string) ($row['is_active'] ?? ''), ['0', '1'], true)) {
            $errors[] = 'Baris ' . $rowNumber . ': status harus 1 untuk aktif atau 0 untuk nonaktif.';
        }

        return $errors;
    }

    private function categoryMap(): array
    {
        $map = [];
        foreach ($this->categoryModel->findAll() as $category) {
            $map[mb_strtolower($category->name)] = (int) $category->id;
        }

        return $map;
    }

    private function csvLine(array $row): string
    {
        $handle = fopen('php://temp', 'rb+');
        fputcsv($handle, $row);
        rewind($handle);
        $line = stream_get_contents($handle);
        fclose($handle);

        return (string) $line;
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

    private function validateImageUpload(): ?string
    {
        $image = $this->request->getFile('image');

        if (! $image || $image->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (! $image->isValid()) {
            return 'Upload gambar tidak valid.';
        }

        if ($image->getSizeByUnit('kb') > 1024) {
            return 'Ukuran gambar maksimal 1MB.';
        }

        if (! in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return 'File harus berupa gambar.';
        }

        return null;
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
