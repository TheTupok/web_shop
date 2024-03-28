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


    public function getHtmlCategoryHierarchyForForm(Category $category): ?string
    {

        $arrayTree = parent::childrenHierarchy();
        $categoryHtml =
            '<ul>
                <li>
                    <input type="radio" id="category_parent_placeholder" name="category[parent]" value="">
                    <label for="category_parent_placeholder">None</label>
                </li>
             </ul> ';
        foreach ($arrayTree as $child) {
            $categoryHtml .= "<ul>";
            $this->drawCategoryForHierarchy($child, $category, $categoryHtml);
            $categoryHtml .= "</ul>";
        }

        return $categoryHtml;
    }

    private function drawCategoryForHierarchy(array $drawCategory, ?Category $currentCategory, string &$html)
    {
        if ($currentCategory !== null
            && $drawCategory['id'] == $currentCategory->getId()) {
            return;
        }
        $html .=
            '<li>
                <input 
                    type="radio" 
                    id="category_parent_' . $drawCategory['id'] . '" 
                    name="category[parent]"';

        if ($currentCategory !== null
            && $currentCategory->getParent() !== null
            && $drawCategory['id'] == $currentCategory->getParent()->getId()) {
            $html .= ' checked ';
        }

        $html .= 'value="' . $drawCategory['id'] . '"><label for="category_parent_' . $drawCategory['id'] . '">' . $drawCategory['name'] . '</label>
             </li>';
        foreach ($drawCategory['__children'] as $child) {
            $html .= "<ul>";
            $this->drawCategoryForHierarchy($child, $currentCategory, $html);
            $html .= "</ul>";
        }
    }
}
