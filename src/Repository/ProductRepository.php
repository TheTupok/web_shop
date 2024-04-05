<?php

namespace App\Repository;

use App\Entity\File;
use App\Entity\Product;
use App\Enum\EntityType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getDetailPictures(Product $product): ArrayCollection
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $detailPictures = $queryBuilder
            ->from(File::class, 'f')
            ->select('f')
            ->where('f.entityId = :productId')
            ->andWhere('f.entityType = :entityType')
            ->andWhere('f.isPreview = false')
            ->setParameters(new ArrayCollection([
                new Parameter('productId', $product->getId()),
                new Parameter('entityType', EntityType::PRODUCT->value),
            ]))
        ;

        return new ArrayCollection(
            $detailPictures
                ->getQuery()
                ->execute()
        );
    }
}
