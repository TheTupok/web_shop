<?php

namespace App\Entity\File;

use App\Entity\Product;
use App\Repository\ProductImageRepository;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\NoReturn;

#[ORM\Entity(repositoryClass: ProductImageRepository::class)]
#[ORM\Table(name: 'products_image')]
#[ORM\HasLifecycleCallbacks]
class ProductImage extends File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $rootPath = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product|null $product = null;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getEntity(): ?Product
    {
        return $this->product;
    }

    public function setEntity(?object $entity): self
    {
        $this->product = $entity;

        return $this;
    }

    public function getRootPath(): ?string
    {
        return $this->rootPath;
    }

    public function setRootPath(?string $rootPath): ProductImage
    {
        $this->rootPath = $rootPath;

        return $this;
    }
}