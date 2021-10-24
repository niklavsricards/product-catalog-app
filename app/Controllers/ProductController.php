<?php

namespace App\Controllers;

use App\Models\Product;
use App\Redirect;
use App\Repositories\Products\MySqlProductsRepository;
use App\Repositories\Products\ProductsRepository;
use App\Repositories\Tags\MySqlTagsRepository;
use App\Repositories\Tags\TagsRepository;
use Carbon\Carbon;
use DI\Container;
use Ramsey\Uuid\Uuid;

class ProductController
{
    private ProductsRepository $productsRepository;
    private TagsRepository $tagsRepository;

    public function __construct(Container $container)
    {
        $this->productsRepository = $container->get(MySqlProductsRepository::class);
        $this->tagsRepository = $container->get(MySqlTagsRepository::class);
    }

    public function index(): void
    {
        $categories = $this->productsRepository->getCategories()->getAll();

        $categoryId = $_GET['category'] ?? '';
        $products = $this->productsRepository->getAll($_SESSION['userId'], $categoryId)->getAll();

        require_once 'app/Views/index.template.php';
    }

    public function createView(): void
    {
        $errors = [];
        $categories = $this->productsRepository->getCategories()->getAll();
        $tags = $this->tagsRepository->getAllTags()->allTags();

        require_once 'app/Views/Products/create.template.php';
    }

    public function create(): void
    {
        $errors = [];
        $categories = $this->productsRepository->getCategories()->getAll();
        $tags = $this->tagsRepository->getAllTags()->allTags();

        $id = Uuid::uuid4();
        $title = trim($_POST['title']);
        $categoryId = $_POST['category'];
        $userId = $_SESSION['userId'];
        $amount = $_POST['amount'];
        $createdAt = Carbon::now()->toDateTimeString('minute');

        $selectecTags = $_POST['tags'] ?? null;

        if (!is_null($selectecTags)) {
            foreach ($selectecTags as $selectecTag) {
                if ($this->tagsRepository->getTagById($selectecTag) == null) {
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

        if ($this->productsRepository->getCategoryById($categoryId) == null) {
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

            Redirect::redirect("/");
        } else {
            require_once 'app/Views/Products/create.template.php';
        }
    }

    public function updateView(array $vars): void
    {
        $id = $vars['id'] ?? null;

        if ($id == null) header('Location: /');

        $product = $this->productsRepository->getOne($id);

        if ($product !== null) {
            $errors = [];
            $categories = $this->productsRepository->getCategories()->getAll();
            require_once 'app/Views/Products/update.template.php';
        } else {
            Redirect::redirect("/");
        }
    }

    public function update(array $vars): void
    {
        $errors = [];
        $categories = $this->productsRepository->getCategories()->getAll();

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

        if ($this->productsRepository->getCategoryById($categoryId) == null) {
            array_push($errors, 'Such product category doesn\'t exist');
        }

        if (empty($errors)) {
            $this->productsRepository->update($id, $title, $categoryId, $amount, $updatedAt);
            Redirect::redirect("/");
        } else {
            require_once 'app/Views/Products/create.template.php';
        }
    }

    public function delete(array $vars): void
    {
        $id = $vars['id'] ?? null;

        if ($id == null) header('Location: /');

        $product = $this->productsRepository->getOne($id);

        if ($product !== null) {
            $this->productsRepository->delete($product);
        }

        Redirect::redirect("/");
    }
}