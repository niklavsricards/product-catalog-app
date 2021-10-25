<?php

namespace App\Validations;

use App\Exceptions\ProductValidationException;
use App\Repositories\Products\MySqlProductsRepository;
use App\Repositories\Tags\MySqlTagsRepository;
use DI\Container;

class ProductFormValidation
{
    private array $errors;
    private MySqlProductsRepository $productsRepository;
    private MySqlTagsRepository $tagsRepository;

    public function __construct(Container $container, ?array $errors = [])
    {
        $this->errors = $errors;
        $this->productsRepository = $container->get(MySqlProductsRepository::class);
        $this->tagsRepository = $container->get(MySqlTagsRepository::class);
    }

    public function errors(): ?array
    {
        return $this->errors;
    }

    public function productFormValidation(array $input): void
    {
        $title = trim($input['title']);
        $categoryId = $input['category'];
        $amount = $input['amount'];
        $selectecTags = $input['tags'] ?? null;

        if (!is_null($selectecTags)) {
            foreach ($selectecTags as $selectecTag) {
                if ($this->tagsRepository->getTagById($selectecTag) == null) {
                    $this->errors[] = 'Invalid product tag provided';
                    break;
                }
            }
        }

        if (empty($title)) {
            $this->errors[] = 'Product title is required';
        }

        if (empty($amount)) {
            $this->errors[] = 'Product amount is required';
        }

        if ($this->productsRepository->getCategoryById($categoryId) == null) {
            $this->errors[] = 'Such product category doesn\'t exist';
        }

        if (count($this->errors) > 0 ) throw new ProductValidationException();
    }
}