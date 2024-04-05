<?php

namespace App\Service;

use App\Entity\File;
use App\Enum\EntityType;
use App\Kernel;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileUploader
{
    public function __construct(
        private string           $targetDirectory,
        private SluggerInterface $slugger,
        private Kernel           $kernel,
    ) {
    }

    public
    function upload(UploadedFile $uploadedFile, object $entity): File
    {
        dd($uploadedFile);
        $reflClass = new \ReflectionClass($entity::class);
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            $movedFile = $uploadedFile->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $entityType = EntityType::{mb_strtoupper($reflClass->getShortName())};

        $file = (new File())
            ->setName($fileName)
            ->setSize($movedFile->getSize())
            ->setRootPath($movedFile->getPathname())
            ->setEntityId($entity->getId())
            ->setEntityType($entityType->value)
            ->setSort(100)
        ;

        $file->setLocalPath(str_replace($this->kernel->getProjectDir() . "/public", "", $file->getRootPath()));

        return $file;
    }

    public
    function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}