<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Transliterator;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends NestedTreeRepository
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(Category::class));
    }

    public function generateCodeName(string $name): string
    {
        $translite = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($name);
        $codeName = strtolower(str_replace(" ", "_", $translite));

        if (!$this->checkCodeName($codeName)) {
            $origCodeName = $codeName;
            $i = 0;
            while (!$this->checkCodeName($codeName)) {
                $codeName = $origCodeName . "_" . $i;
                $i++;
            }
        }

        return $codeName;
    }

    private function checkCodeName(string $codeName): bool
    {
        $category = $this->findBy(["codeName" => $codeName]);

        return count($category) == 0;
    }

    public function getByCodeName(?string $category): ?Category
    {
        return $this->createQueryBuilder('c')
            ->setMaxResults(1)
            ->where('c.codeName = :codeName')
            ->setParameter('codeName', $category)
            ->getQuery()
            ->getResult()[0];
    }

    public function getAllProductsFromCategories(Category $category): ArrayCollection
    {
        $productCollection = new ArrayCollection($category->getProducts()->getValues());
        foreach ($this->getChildren($category) as $childCategory) {
            foreach ($childCategory->getProducts()->getValues() as $product) {
                $productCollection->add($product);
            }
        }

        return $productCollection;
    }
}
