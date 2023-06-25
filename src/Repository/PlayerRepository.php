<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function save(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /***
     * Prepare QuerySet to be used with paginator
     */
    public function getPaginatorQuery(?string $search=null, ?int $team=null): Query
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select(['p.id, p.name, p.surname, t.name as team'])
            ->join('p.team', 't');

        if ($search)
            $queryBuilder->where("p.name LIKE :search OR p.surname LIKE :search")
                  ->setParameter('search', "%{$search}%");

        if ($team)
            $queryBuilder->where("t.id = :teamId")
                ->setParameter('teamId', $team);

        return $queryBuilder->getQuery();
    }
}
