<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $editCategory = null;
        $editId = $this->request->getGet('edit');

        if ($editId !== null && $editId !== '') {
            $editCategory = $this->categoryModel->find((int) $editId);

            if (! $editCategory) {
                return redirect()->to('/category')
                    ->with('error', 'Kategori yang ingin diedit tidak ditemukan.');
            }
        }

        return view('category/index', [
            'title'        => 'Kategori',
            'categories'   => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
            'editCategory' => $editCategory,
        ]);
    }

    public function store()
    {
        $rules = [
            'name' => [
                'label'  => 'Nama Kategori',
                'rules'  => 'required|min_length[2]|max_length[50]|is_unique[categories.name]',
                'errors' => $this->validationMessages(),
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->insert([
            'name' => trim((string) $this->request->getPost('name')),
        ]);

        return redirect()->to('/category')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update($id)
    {
        $category = $this->categoryModel->find((int) $id);

        if (! $category) {
            return redirect()->to('/category')->with('error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'name' => [
                'label'  => 'Nama Kategori',
                'rules'  => "required|min_length[2]|max_length[50]|is_unique[categories.name,id,{$category->id}]",
                'errors' => $this->validationMessages(),
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/category?edit=' . $category->id)
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->update($category->id, [
            'name' => trim((string) $this->request->getPost('name')),
        ]);

        return redirect()->to('/category')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id)
    {
        $category = $this->categoryModel->find((int) $id);

        if (! $category) {
            return redirect()->to('/category')->with('error', 'Kategori tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $productCount = $db->table('products')->where('category_id', $category->id)->countAllResults();

        if ($productCount > 0) {
            return redirect()->to('/category')
                ->with('error', "Kategori tidak dapat dihapus karena masih digunakan oleh {$productCount} produk.");
        }

        $this->categoryModel->delete($category->id);

        return redirect()->to('/category')->with('success', 'Kategori berhasil dihapus.');
    }

    private function validationMessages(): array
    {
        return [
            'required'   => 'Nama kategori wajib diisi.',
            'min_length' => 'Nama kategori minimal 2 karakter.',
            'max_length' => 'Nama kategori maksimal 50 karakter.',
            'is_unique'  => 'Nama kategori sudah digunakan.',
        ];
    }
}
