<?php

namespace App\Repositories\Tags;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;
use PDO;

class MySqlTagsRepository implements TagsRepository
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

    public function getTags(): TagsCollection
    {
        $collection = new TagsCollection();

        $statement = $this->pdo->prepare('SELECT * FROM tags');
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $tag)
        {
            $collection->add(
                new Tag(
                    $tag['id'],
                    $tag['name']
                )
            );
        }

        return $collection;
    }

    public function add(array $tags, string $productId): void
    {
        foreach ($tags as $tag)
        {
            $statement = $this->pdo->prepare('INSERT INTO products_tags (product_id, tag_id) VALUES (?, ?)');
            $statement->execute([$productId, $tag]);
        }
    }
}