<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/catalog')]
class CatalogController extends AbstractController
{
    #[Route('/', name: 'app_catalog')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/{codeName}', name: 'app_catalog_category')]
    public function showCategoryProduct(
        $codeName,
        CategoryRepository $categoryRepository
    ): Response {
        $category = $categoryRepository->findBy(['codeName' => $codeName])[0];

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'products' => $category->getProducts(),
        ]);
    }
}
