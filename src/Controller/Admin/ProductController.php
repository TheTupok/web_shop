<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


#[Route('/admin/catalog/product')]
class ProductController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        Product                $product,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $url = $product->getCategory() ?
                $this->generateUrl('app_admin_catalog_category', ['codeName' => $product->getCategory()->getCodeName()]) :
                $this->generateUrl('app_admin_catalog');

            return $this->redirect($url);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form'    => $form,
        ]);
    }

    #[Route('/new', name: 'app_admin_product_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $em,
        FileUploader           $fileUploader
    ): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();

            $images = $form->get('image')->getData();
            foreach ($images as $file) {
                $file = $fileUploader->upload($file, $product);
                $em->persist($file);
            }
            $em->flush();

            $url = $product->getCategory() ?
                $this->generateUrl('app_admin_catalog_category', ['codeName' => $product->getCategory()->getCodeName()]) :
                $this->generateUrl('app_admin_catalog');

            return $this->redirect($url);
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form'    => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_product_delete', methods: ['POST'])]
    public function delete(
        Request                $request,
        Product                $product,
        EntityManagerInterface $em
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->get('_token'))) {
            $em->remove($product);
            $em->flush();
        }

        $url = $product->getCategory() ?
            $this->generateUrl('app_admin_catalog_category', ['codeName' => $product->getCategory()->getCodeName()]) :
            $this->generateUrl('app_admin_catalog');

        return $this->redirect($url);
    }
}
