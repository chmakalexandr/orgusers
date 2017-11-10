<?php

namespace Intex\OrgBundle\Entity\Repository;

use Intex\OrgBundle\Entity\User;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return array
     */
    public function getAllUsers()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->addOrderBy('u.lastname', 'ASC');
      return $qb->getQuery()
            ->getResult();
    }

    public function getAllInn()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.inn');
        return $qb->getQuery()
            ->getResult();
    }


    /**
     * Checks if there is user in the database
     * @param User $user
     * @return bool
     */
    public function isUniqueUser(User $user)
    {
        $db = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.inn = :inn')
            ->setParameter('inn', $user->getInn());
        $inn= $db->getQuery()->getResult();

        /*8$db = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.snils = :snils')
            ->setParameter('snils', $user->getSnils());
        $snils = $db->getQuery()->getResult();
        */


        return $inn;
    }
}
