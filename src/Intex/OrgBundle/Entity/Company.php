<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 10:57
 */

namespace Intex\OrgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Intex\OrgBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlList;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation as JMS;


/**
 * @ORM\Entity(repositoryClass="Intex\OrgBundle\Entity\Repository\CompanyRepository")
 * @ORM\Table(name="company")
 *
 * @JMS\XmlRoot("org")
 */
class Company
{
    /**
     * @Assert\NotBlank()
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     * @JMS\XmlAttribute
     * @JMS\SerializedName("displayName")
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="bigint")
     * @JMS\XmlAttribute
     */
    protected $ogrn;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="bigint")
     * @JMS\XmlAttribute
     */
    protected $oktmo;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="company")
     *
     * @Type("ArrayCollection<Intex\OrgBundle\Entity\User>")
     * @JMS\XmlList(inline = true, entry = "Intex\OrgBundle\Entity\User")
     */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function addUser(User $user)
    {
        $this->users[] = $user;
    }

    public function getUsers()
    {
        return $this->users;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * @param mixed $ogrn
     */
    public function setOgrn($ogrn)
    {
        $this->ogrn = $ogrn;
    }

    /**
     * @return mixed
     */
    public function getOktmo()
    {
        return $this->oktmo;
    }

    /**
     * @param mixed $oktmo
     */
    public function setOktmo($oktmo)
    {
        $this->oktmo = $oktmo;
    }


    /**
     * Remove user
     *
     * @param \Intex\OrgBundle\Entity\User $user
     */
    public function removeUser(\Intex\OrgBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    public function __toString()
    {
        return $this->getName();
    }
}
