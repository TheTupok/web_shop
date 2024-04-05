<?php

namespace App\Controller\Admin;

use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/file')]
class FileController extends AbstractController
{
    #[Route('/{id}', name: 'app_admin_file_delete', methods: ['POST'])]
    public function delete(
        Request                $request,
        File                   $file,
        EntityManagerInterface $entityManager
    ): Response {
            $entityManager->remove($file);
            $entityManager->flush();

        return $this->redirect($request->get('redirectUrl'));
    }
}