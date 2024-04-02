<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/catalog')]
class CatalogController extends AbstractController
{
    #[Route('/', name: 'app_admin_catalog', methods: ['GET'])]
    public function index(
        EntityManagerInterface $em,
        ProductRepository $productRepository
    ): Response {
        $categoryRepository = $em->getRepository(Category::class);

        return $this->render('admin/catalog/index.html.twig', [
            'category'   => null,
            'products'   => $productRepository->findAll(),
            'categories' => $categoryRepository->findBy(['parent' => null]),
        ]);
    }

    #[Route('/{codeName}', name: 'app_admin_catalog_category')]
    public function showCategoryProduct(
        $codeName,
        CategoryRepository $categoryRepository,
    ): Response {
        $category = $categoryRepository->getByCodeName($codeName);

        return $this->render('admin/catalog/index.html.twig', [
            'category'   => $categoryRepository->getByCodeName($codeName),
            'categories' => $categoryRepository->getChildren($category, true),
            'products'   => $categoryRepository->getAllProductsFromCategories($category),
        ]);
    }
}
