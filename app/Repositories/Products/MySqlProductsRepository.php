<?php

namespace App\Repositories\Products;

use App\Models\Collections\ProductCategoryCollection;
use App\Models\Collections\ProductCollection;
use App\Models\Product;
use App\Models\ProductCategory;
use PDO;

class MySqlProductsRepository implements ProductsRepository
{
    public PDO $pdo;

    public function __construct($config)
    {
        $this->pdo = new PDO(
            "mysql:host={$config['host']};
            port={$config['port']};
            dbname={$config['dbName']}",
            "{$config['username']}",
            "{$config['password']}"
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getCategories(): ProductCategoryCollection
    {
        $collection = new ProductCategoryCollection();

        $statement = $this->pdo->prepare('SELECT * FROM product_categories');
        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $item) {
            $collection->add(new ProductCategory(
                $item['id'],
                $item['name']
            ));
        }

        return $collection;
    }

    public function add(Product $product): void
    {
        $statement = $this->pdo->prepare('INSERT INTO products (id, title, category_id, 
        user_id, amount, created_at, updated_at) VALUES (:id, :title, :category_id, :user_id, :amount, 
                                                   :created_at, :updated_at)');
        $statement->bindValue(':id', $product->getId());
        $statement->bindValue(':title', $product->getTitle());
        $statement->bindValue(':category_id', $product->getCategory());
        $statement->bindValue(':user_id', $product->getUserId());
        $statement->bindValue(':amount', $product->getAmount());
        $statement->bindValue(':created_at', $product->getCreatedAt());
        $statement->bindValue(':updated_at', $product->getUpdatedAt());

        $statement->execute();
    }

    public function getAll(string $userId, string $categoryId): ProductCollection
    {
        $collection = new ProductCollection();

        if (empty($categoryId)) {
            $statement = $this->pdo->prepare('SELECT * FROM products WHERE user_id = :user_id 
                    ORDER BY created_at DESC');
            $statement->bindValue(':user_id', $userId);
        } else {
            $statement = $this->pdo->prepare('SELECT * FROM products WHERE user_id = :user_id 
                         AND category_id = :category_id ORDER BY created_at DESC');
            $statement->bindValue(':user_id', $userId);
            $statement->bindValue(':category_id', $categoryId);
        }

        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $product)
        {
            $collection->add(new Product(
                $product['id'],
                $product['title'],
                $product['category_id'],
                $product['user_id'],
                $product['amount'],
                $product['created_at'],
                $product['updated_at']
            ));
        }

        return $collection;
    }

    public function getOne(string $id): ?Product
    {
        $statement = $this->pdo->prepare('SELECT * FROM products WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        $product = $statement->fetch(PDO::FETCH_ASSOC);

        if (empty($product)) return null;

        return new Product(
            $product['id'],
            $product['title'],
            $product['category_id'],
            $product['user_id'],
            $product['amount'],
            $product['created_at'],
            $product['updated_at']
        );
    }

    public function update(string $id, string $title, string $categoryId, int $amount, string $updatedAt): void
    {
        $statement = $this->pdo->prepare('UPDATE products SET title = ?, category_id = ?, 
                    amount = ?, updated_at = ? WHERE id = ?');
        $statement->execute([$title, $categoryId, $amount, $updatedAt, $id]);
    }

    public function delete(Product $product): void
    {
        $statement = $this->pdo->prepare('DELETE FROM products WHERE id = :id');
        $statement->bindValue(':id', $product->getId());
        $statement->execute();
    }
}
