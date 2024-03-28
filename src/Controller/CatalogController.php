<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/catalog')]
class CatalogController extends AbstractController
{
    #[Route('/', name: 'app_catalog', methods: ['GET'])]
    public function index(
        EntityManagerInterface $em,
    ): Response {
        $categoryRepository = $em->getRepository(Category::class);

        return $this->render('catalog/index.html.twig', [
            'category'   => null,
            'products'   => null,
            'categories' => $categoryRepository->findBy(['parent' => null]),
        ]);
    }

    #[Route('/{codeName}', name: 'app_catalog_category')]
    public function showCategoryProduct(
        $codeName,
        CategoryRepository $categoryRepository,
    ): Response {
        $category = $categoryRepository->getByCodeName($codeName);

        return $this->render('catalog/index.html.twig', [
            'category'   => $categoryRepository->getByCodeName($codeName),
            'categories' => $categoryRepository->getChildren($category, true),
            'products'   => $categoryRepository->getAllProductsFromCategories($category),
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('catalog/product.html.twig', [
            'product' => $product,
        ]);
    }
}
