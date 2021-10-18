<?php

namespace App\Models\Collections;

use App\Models\ProductCategory;

class ProductCategoriesCollection
{
    private array $categories = [];

    public function __construct(array $categories = [])
    {
        foreach ($categories as $category) $this->add($category);
    }

    public function add(ProductCategory $category): void
    {
        $this->categories[$category->getId()] = $category;
    }

    public function getAll(): array
    {
        return $this->categories;
    }
}