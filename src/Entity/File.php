<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\NoReturn;

#[ORM\Entity(repositoryClass: FileRepository::class)]
#[ORM\HasLifecycleCallbacks]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $size = null;

    #[ORM\Column(length: 255)]
    private ?string $localPath = null;

    #[ORM\Column(length: 255)]
    private ?string $rootPath = null;

    #[ORM\Column]
    private ?int $sort = null;

    #[ORM\Column(options: ["default" => false])]
    private bool $isPreview = false;

    #[ORM\Column]
    private ?int $entityType = null;

    #[ORM\Column]
    private ?int $entityId = null;

    #[NoReturn] #[ORM\PreRemove]
    public function doUnlinkFilePreRemove(PreRemoveEventArgs $eventArgs): void
    {
        /**
         * @var $file File
         */
        $file = $eventArgs->getObject();
        unlink($file->getRootPath());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getLocalPath(): ?string
    {
        return $this->localPath;
    }

    public function setLocalPath(string $localPath): static
    {
        $this->localPath = $localPath;

        return $this;
    }

    public function getRootPath(): ?string
    {
        return $this->rootPath;
    }

    public function setRootPath(string $rootPath): static
    {
        $this->rootPath = $rootPath;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    public function isPreview(): bool
    {
        return $this->isPreview;
    }

    public function setIsPreview(bool $isPreview): File
    {
        $this->isPreview = $isPreview;

        return $this;
    }

    public function getEntityType(): ?int
    {
        return $this->entityType;
    }

    public function setEntityType(?int $entityType): File
    {
        $this->entityType = $entityType;
        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(?int $entityId): File
    {
        $this->entityId = $entityId;
        return $this;
    }
}
