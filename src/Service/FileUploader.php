<?php

namespace App\Service;

use App\Entity\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileUploader
{
    public function __construct(
        private string           $targetDirectory,
        private SluggerInterface $slugger,
        private KernelInterface  $kernel,
    ) {
    }

    public
    function upload(
        UploadedFile $uploadedFile,
        object       $entity,
        string       $fileClass
    ): ?File {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            $movedFile = $uploadedFile->move($this->getTargetDirectory(), $fileName);

            return (new $fileClass())
                ->setName($fileName)
                ->setPath(str_replace($this->kernel->getProjectDir() . "/public", "", $movedFile->getPathname()))
                ->setEntity($entity)
                ->setRootPath($movedFile->getPathname())
            ;
        } catch (FileException) {
            // ... handle exception if something happens during file upload
            return null;
        }
    }

    public
    function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}