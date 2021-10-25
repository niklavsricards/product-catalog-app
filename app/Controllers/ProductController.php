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
        $categories = $this->productsRepository->getCategories()->getAll();
        $tags = $this->tagsRepository->getAllTags()->allTags();
        require_once 'app/Views/Products/create.template.php';
    }

    public function create(): void
    {
        $selectecTags = $_POST['tags'] ?? null;

        $product = new Product(
            Uuid::uuid4(),
            trim($_POST['title']),
            $this->productsRepository->getCategoryById($_POST['category']),
            $_SESSION['userId'],
            $_POST['amount'],
            Carbon::now()->toDateTimeString('minute')
        );

        $this->productsRepository->add($product);

        if (!is_null($selectecTags)) {
            $this->tagsRepository->add($selectecTags, $product->getId());
        }

        Redirect::redirect("/");
    }

    public function updateView(array $vars): void
    {
        $id = $vars['id'] ?? null;

        if ($id == null) Redirect::redirect("/");

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
        $this->productsRepository->update(
            $vars['id'],
            trim($_POST['title']),
            $this->productsRepository->getCategoryById($_POST['category']),
            $_POST['amount'],
            Carbon::now()->toDateTimeString('minute')
        );
        Redirect::redirect("/");
    }

    public function delete(array $vars): void
    {
        $id = $vars['id'] ?? null;
        if ($id == null) Redirect::redirect("/");
        $product = $this->productsRepository->getOne($id);

        if ($product !== null) {
            $this->productsRepository->delete($product);
        }
        Redirect::redirect("/");
    }
}