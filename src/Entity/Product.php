<?php

namespace App\Entity;

use App\Entity\File\ProductImage;
use App\Entity\File\ProductVariantsImage;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImage::class)]
    private ArrayCollection|PersistentCollection $images;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Category|null $category = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Product::class)]
    private ArrayCollection|PersistentCollection $productSKU;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productSKU')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product|null $product = null;

    public function __construct()
    {
        $this->productSKU = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Product
    {
        $this->name = $name;

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

    public function getImages(): ArrayCollection|PersistentCollection
    {
        return $this->images;
    }

    public function setImages(ArrayCollection|PersistentCollection $image): Product
    {
        $this->images = $image;

        return $this;
    }

    public function getProductSKU(): ArrayCollection|PersistentCollection
    {
        return $this->productSKU;
    }

    public function setProductSKU(ArrayCollection|PersistentCollection $productSKU): Product
    {
        $this->productSKU = $productSKU;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): Product
    {
        $this->product = $product;

        return $this;
    }
}
