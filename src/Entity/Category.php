<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Transliterator;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $codeName = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category')]
    private ArrayCollection|PersistentCollection $products;

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

    public function __toString(): string
    {
        return $this->name;
    }

    public function getProducts(): ArrayCollection|PersistentCollection
    {
        return $this->products;
    }

    public function setProducts(ArrayCollection|PersistentCollection $products): Category
    {
        $this->products = $products;

        return $this;
    }

    public function getCodeName(): ?string
    {
        return $this->codeName;
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function setCodeName(): Category
    {
        $translite = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($this->name);
        $this->codeName = strtolower(str_replace(" ", "_", $translite));

        return $this;
    }
}
