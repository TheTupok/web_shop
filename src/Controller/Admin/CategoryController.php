<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        CategoryRepository     $categoryRepository
    ): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $categoryTree = $categoryRepository->getHtmlCategoryHierarchyForForm($category);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_catalog', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category/new.html.twig', [
            'category'     => $category,
            'form'         => $form,
            'categoryTree' => $categoryTree
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        Category               $category,
        EntityManagerInterface $entityManager,
        CategoryRepository     $categoryRepository
    ): Response {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $categoryTree = $categoryRepository->getHtmlCategoryHierarchyForForm($category);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_catalog', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category/edit.html.twig', [
            'category'     => $category,
            'form'         => $form,
            'categoryTree' => $categoryTree
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_catalog', [], Response::HTTP_SEE_OTHER);
    }
}
