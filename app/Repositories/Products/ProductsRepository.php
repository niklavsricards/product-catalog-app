<?php

namespace App\Repositories\Products;

use App\Models\Collections\ProductCategoriesCollection;
use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use App\Models\ProductCategory;

interface ProductsRepository
{
    public function getCategories(): ProductCategoriesCollection;
    public function getCategoryById(string $id): ?ProductCategory;
    public function add(Product $product): void;
    public function getAll(string $userId, string $categoryId): ProductsCollection;
    public function getOne(string $id): ?Product;
    public function update(string $id, string $title, ProductCategory $category, int $amount, string $updatedAt): void;
    public function delete(Product $product): void;
}