<?php

namespace App\Middleware;

use App\Exceptions\ProductValidationException;
use App\Redirect;
use App\Validations\ProductFormValidation;
use DI\Container;

class ProductFormValidationMiddleware implements Middleware
{
    private ProductFormValidation $validation;

    public function __construct(Container $container)
    {
        $this->validation = $container->get(ProductFormValidation::class);
    }

    public function handle(): void
    {
        try {
            $this->validation->productFormValidation($_POST);
        } catch (ProductValidationException $exception) {
            $_SESSION['errors'] = $this->validation->errors();
            Redirect::redirect('/products/create');
        }
    }
}