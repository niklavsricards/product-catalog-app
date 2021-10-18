<?php

namespace App\Models;

class Product
{
    private string $id;
    private string $title;
    private int $categoryId;
    private string $userId;
    private int $amount;
    private string $createdAt;
    private ?string $updatedAt;

    public function __construct(string $id, string $title, int $categoryId, string $userId,
                                int $amount, string $createdAt, string $updatedAt = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->categoryId = $categoryId;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): int
    {
        return $this->categoryId;
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
}