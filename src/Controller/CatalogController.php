<?php

namespace App\Controller;

use App\Entity\Category;
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

        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findBy(['parent' => null]),
        ]);
    }

    #[Route('/{codeName}', name: 'app_catalog_category')]
    public function showCategoryProduct(
        $codeName,
        EntityManagerInterface $em
    ): Response {
        $repo = $em->getRepository(Category::class);
        $category = $repo->findBy(['codeName' => $codeName])[0];

        return $this->render('category/show.html.twig', [
            'category'          => $category,
            'listChildCategory' => $repo->getChildren($category, true),
            'parentCategory'    => $category->getParent(),
            'products'          => $category->getProducts(),
        ]);
    }
}
