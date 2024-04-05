<?php

namespace App\Repository;

use App\Entity\File;
use App\Enum\EntityType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use ReflectionClass;
use ReflectionException;

/**
 * @extends ServiceEntityRepository<File>
 *
 * @method File|null find($id, $lockMode = null, $lockVersion = null)
 * @method File|null findOneBy(array $criteria, array $orderBy = null)
 * @method File[]    findAll()
 * @method File[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    /**
     * @throws ReflectionException
     */
    public function deleteFilesEntity(object $entity): bool
    {
        $reflClass = new ReflectionClass($entity::class);
        $entityType = EntityType::{mb_strtoupper($reflClass->getShortName())};

        $builder = $this->createQueryBuilder('f');

        $builder
            ->delete(File::class, 'f')
            ->where('f.entityType = :entity_type')
            ->andWhere('f.entityId = :entity_id')
            ->setParameters(new ArrayCollection([
                new Parameter('entity_type', $entityType->value),
                new Parameter('entity_id', $entity->getId()),
            ]))
        ;

        return $builder->getQuery()->execute() > 0;
    }
}
