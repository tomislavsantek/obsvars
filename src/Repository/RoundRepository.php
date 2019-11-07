<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Round;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;

/**
 * @method Round|null find($id, $lockMode = null, $lockVersion = null)
 * @method Round|null findOneBy(array $criteria, array $orderBy = null)
 * @method Round[]    findAll()
 * @method Round[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Round::class);
    }

    /**
     * @return Round|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUpcomingRound(){
        try{
            return $this->createQueryBuilder('r')
                ->where('r.state = :state')
                ->setParameter('state', Round::STATE_PENDING)
                ->orderBy('r.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e){
            return new Round();
        }
    }

    /**
     * @return Round|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findCurrentRound(){
        try{
            return $this->createQueryBuilder('r')
                ->where('r.state = :state')
                ->setParameter('state', Round::STATE_IN_PROGRESS)
                ->orderBy('r.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e){
            return new Round();
        }
    }

    /**
     * @return Round[]
     */
    public function findCompleteRounds(){
        return $this->createQueryBuilder('r')
            ->where('r.state = :state')
            ->setParameter('state', Round::STATE_COMPLETE)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }



    /**
     * Gets the rounds sorted by their execution schedule (upcoming first, finished last)
     * @return Round[]
     */
    public function findAllOrderedByStateAndId(){
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Round $round
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function finalizePreviousRounds(Round $r){
        $previousRounds = $this->createQueryBuilder('r')
            ->where("r.id < :id")
            ->setParameter('id', $r->getId())
            ->getQuery()
            ->getResult();

        foreach($previousRounds as $round){
            /** @var Round $round */
            $round->setState(Round::STATE_COMPLETE);
            $this->getEntityManager()->persist($round);
        }
        return $this->getEntityManager()->flush();
    }

    /**
     * @param Round $r
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resetUpcomingRounds(Round $r){
        $upcomingRounds = $this->createQueryBuilder('r')
            ->where('r.id > :id')
            ->setParameter('id', $r->getId())
            ->getQuery()
            ->getResult();

        foreach($upcomingRounds as $round){
            /** @var Round $round */
            $round->setState(Round::STATE_PENDING);
            $this->getEntityManager()->persist($round);
        }
        return $this->getEntityManager()->flush();
    }

    /**
     * Deletes all rounds that have specified player
     * @param Player $player
     * @return mixed
     */
    public function deleteRoundsByPlayer(Player $player){
        return $this->createQueryBuilder('r')
            ->delete()
            ->where('r.Player1 = :player')
            ->orWhere('r.Player2 = :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->execute();
    }

    // /**
    //  * @return Round[] Returns an array of Round objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Round
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
