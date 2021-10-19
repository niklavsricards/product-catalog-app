<?php

namespace App\Controllers;

use App\Models\Collections\ProductCategoriesCollection;
use App\Models\Product;
use App\Repositories\Products\MySqlProductsRepository;
use App\Repositories\Products\ProductsRepository;
use App\Repositories\Tags\MySqlTagsRepository;
use App\Repositories\Tags\TagsRepository;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class ProductController
{
    private ProductsRepository $productsRepository;
    private ProductCategoriesCollection $categoryCollection;
    private TagsRepository $tagsRepository;
    private array $categories;
    private array $validTagIds;

    public function __construct()
    {
        $config = require_once 'config.php';
        $this->productsRepository = new MySqlProductsRepository($config);
        $this->tagsRepository = new MySqlTagsRepository($config);

        $this->categoryCollection = $this->productsRepository->getCategories();

        foreach ($this->categoryCollection->getAll() as $category) {
            $this->categories[$category->getName()] = $category->getId();
        }

        foreach ($this->tagsRepository->getTags()->allTags() as $tag) {
            $this->validTagIds[$tag->name()] = $tag->id();
        }


    }

    public function index(): void
    {
        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
        }

        $categoryId = $_GET['category'] ?? '';

        $categories = $this->categoryCollection->getAll();

        $products = $this->productsRepository->getAll($_SESSION['userId'], $categoryId)->getAll();

        require_once 'app/Views/index.template.php';
    }

    public function createView(): void
    {
        $errors = [];
        $categories = $this->categoryCollection->getAll();
        $tags = $this->tagsRepository->getTags()->allTags();

        require_once 'app/Views/Products/create.template.php';
    }

    public function create(): void
    {
        $errors = [];
        $categories = $this->categoryCollection->getAll();
        $tags = $this->tagsRepository->getTags()->allTags();

        $id = Uuid::uuid4();
        $title = trim($_POST['title']);
        $categoryId = $_POST['category'];
        $userId = $_SESSION['userId'];
        $amount = $_POST['amount'];
        $createdAt = Carbon::now()->toDateTimeString('minute');

        $selectecTags = $_POST['tags'] ?? null;

        if (!is_null($selectecTags)) {
            foreach ($selectecTags as $selectecTag) {
                if (!in_array($selectecTag, $this->validTagIds)) {
                    array_push($errors, 'Invalid product tag provided');
                }
            }
        }

        if (empty($title)) {
            array_push($errors, 'Product title is required');
        }

        if (empty($amount)) {
            array_push($errors, 'Product amount is required');
        }

        if (!in_array($categoryId, $this->categories)) {
            array_push($errors, 'Such product category doesn\'t exist');
        }

        if (empty($errors)) {
            $product = new Product(
                $id,
                $title,
                $categoryId,
                $userId,
                $amount,
                $createdAt
            );

            $this->productsRepository->add($product);

            if (!is_null($selectecTags)) {
                $this->tagsRepository->add($selectecTags, $product->getId());
            }

            header('Location: /');
        } else {
            require_once 'app/Views/Products/create.template.php';
        }
    }

    public function updateView(array $vars): void
    {
        $id = $vars['id'] ?? null;


        if ($id == null) header('Location: /');

        $product = $this->productsRepository->getOne($id);


        if ($product !== null)
        {
            $errors = [];
            $categories = $this->categoryCollection->getAll();
            require_once 'app/Views/Products/update.template.php';
        } else {
            header('Location: /');
        }
    }

    public function update(array $vars): void
    {
        $errors = [];
        $categories = $this->categoryCollection->getAll();

        $id = $vars['id'];
        $title = trim($_POST['title']);
        $categoryId = $_POST['category'];
        $amount = $_POST['amount'];
        $updatedAt = Carbon::now()->toDateTimeString('minute');

        if (empty($title)) {
            array_push($errors, 'Product title is required');
        }

        if (empty($amount)) {
            array_push($errors, 'Product amount is required');
        }

        if (!in_array($categoryId, $this->categories)) {
            array_push($errors, 'Such product category doesn\'t exist');
        }

        if (empty($errors)) {
            $this->productsRepository->update($id, $title, $categoryId, $amount, $updatedAt);

            header('Location: /');
        } else {
            require_once 'app/Views/Products/create.template.php';
        }
    }

    public function delete(array $vars): void
    {
        $id = $vars['id'] ?? null;

        if ($id == null) header('Location: /');

        $product = $this->productsRepository->getOne($id);

        if ($product !== null)
        {
            $this->productsRepository->delete($product);
        }

        header('Location: /');
    }
}