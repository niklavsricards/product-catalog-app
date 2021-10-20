<?php

namespace App\Repositories\Tags;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;

interface TagsRepository
{
    public function getAllTags(): TagsCollection;
    public function getTagById(string $tagId): Tag;
    public function getTagsForProduct(string $productId): TagsCollection;
    public function add(array $tags, string $productId): void;
}