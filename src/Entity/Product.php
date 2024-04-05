<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $previewPictureId = null;

    #[ORM\OneToOne(targetEntity: File::class)]
    #[ORM\JoinColumn(name: 'preview_picture_id', referencedColumnName: 'id')]
    private ?File $previewPicture = null;

    private ?ArrayCollection $detailPictures = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Category|null $category = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): Product
    {
        $this->category = $category;

        return $this;
    }

    public function getPreviewPictureId(): ?int
    {
        return $this->previewPictureId;
    }

    public function setPreviewPictureId(?int $previewPictureId): Product
    {
        $this->previewPictureId = $previewPictureId;

        return $this;
    }

    public function getPreviewPicture(): ?File
    {
        return $this->previewPicture;
    }

    public function setPreviewPicture(File $previewPicture): Product
    {
        $this->previewPicture = $previewPicture;

        return $this;
    }

    public function getDetailPictures(): ?ArrayCollection
    {
        return $this->detailPictures;
    }

    public function setDetailPictures(ArrayCollection $detailPictures): Product
    {
        $this->detailPictures = $detailPictures;

        return $this;
    }
}
