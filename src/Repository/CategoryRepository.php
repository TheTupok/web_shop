<?php

namespace App\Repository;

use App\Entity\Category;
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
}
