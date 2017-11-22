<?php

namespace Intex\OrgBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Return users from array $users that exist in DB
     * @param ArrayCollection $users
     * @return ArrayCollection
     */
    public function getNoExistingUsers(ArrayCollection $users)
    {
        $usersInns = array();
        foreach ($users as $human){
            $usersInns[] = $human->getInn();
        }

        $db = $this->createQueryBuilder('u')
            ->select('u.inn')
            ->where('u.inn IN (:inns)')
            ->setParameter('inns', $usersInns);
        $getExistingInn = $db->getQuery()->getResult();

        foreach ($getExistingInn as $inn){
            $inns[] = $inn['inn'];
        }

        $newInns = array_diff($usersInns, $inns);
        $newUsers = array();
        if ($newInns) {
            foreach ($users as $human) {
                if (in_array($human->getInn(), $newInns)){
                    $newUsers[] = $human;
                }
            }
        }

        return $newUsers;
    }
}
