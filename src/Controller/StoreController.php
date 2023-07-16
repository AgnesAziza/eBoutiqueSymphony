<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    /**
     * @Route("/store", name="store")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('store/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/store/category/{id}", name="store_category")
     */
    public function category(CategoryRepository $categoryRepository, int $id): Response
    {
        $category = $categoryRepository->find($id);

        return $this->render('store/category.html.twig', [
            'category' => $category,
            'products' => $category->getProducts(),
        ]);
    }

    /**
     * @Route("/store/product/{id}", name="store_product")
     */
    public function product(ProductRepository $productRepository, int $id): Response
    {
        return $this->render('store/product.html.twig', [
            'product' => $productRepository->find($id),
        ]);
    }
}
