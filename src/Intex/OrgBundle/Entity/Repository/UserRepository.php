<?php

namespace Intex\OrgBundle\Entity\Repository;
use Intex\OrgBundle\Entity\User as User;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllUsers()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->addOrderBy('u.lastname', 'ASC');
      return $qb->getQuery()
            ->getResult();
    }

    public function isUniqueUser(User $user)
    {
        $db = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.inn = :inn')
            ->setParameter('inn', $user->getInn());
        $inn= $db->getQuery()->getResult();

        $db = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.snils = :snils')
            ->setParameter('snils', $user->getSnils());
        $snils = $db->getQuery()->getResult();

        if ($inn||$snils) {
            return false;
        }
        return true;
    }
}
