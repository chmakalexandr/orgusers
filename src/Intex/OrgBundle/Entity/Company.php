<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 10:57
 */

namespace Intex\OrgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="Intex\OrgBundle\Entity\Repository\CompanyRepository")
 * @UniqueEntity(
 *     fields={"ogrn"},
 *     message="This organization is already exist."
 * )
 * @ORM\Table(name="company")
 *
 * @JMS\ExclusionPolicy("all")
 *
 */
class Company
{
    /**
     * @Assert\NotBlank()
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     *
     * @JMS\Expose
     * @JMS\XmlAttribute
     * @JMS\SerializedName("displayName")
     */
    protected $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d{13}$/")
     * @ORM\Column(type="bigint",unique=true)
     *
     * @JMS\Type("string")
     * @JMS\Expose
     * @JMS\XmlAttribute
     */
    protected $ogrn;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d{11}$/")
     * @ORM\Column(type="bigint")
     *
     * @JMS\Type("string")
     * @JMS\Expose
     * @JMS\XmlAttribute
     */
    protected $oktmo;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="company")
     *
     * @JMS\Expose
     * @JMS\Type("ArrayCollection<Intex\OrgBundle\Entity\User>")
     * @JMS\XmlList(inline=true, entry="user")
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
