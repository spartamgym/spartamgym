<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function save(Card $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        $flush && $this->getEntityManager()->flush();
    }

    // Obtiene la primera card
 

    public function getFirstCard(): ?Card
    {
        return $this->findOneBy([], ['id' => 'ASC']);
    }

    public function getLastCard(): ?Card
    {
        return $this->findOneBy(['active' => true],['id' => 'DESC']);
    }

    public function getAllCardsActive(): ?array
    {
        return $this->findBy(['active' => true]);
    }

    public function clear(): void
    {
        $this->getEntityManager()->clear();
    }

}
