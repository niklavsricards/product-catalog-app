<?php

namespace App\Models;

use App\Models\Collections\TagsCollection;

class Product
{
    private string $id;
    private string $title;
    private ProductCategory $category;
    private string $userId;
    private int $amount;
    private string $createdAt;
    private ?string $updatedAt;
    private ?TagsCollection $tags;

    public function __construct(string $id, string $title, ProductCategory $category, string $userId,
                                int $amount, string $createdAt, string $updatedAt = null, TagsCollection $tags = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->tags = $tags;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): ProductCategory
    {
        return $this->category;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTags(): TagsCollection
    {
        return $this->tags;
    }
}