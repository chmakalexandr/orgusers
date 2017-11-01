<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 10:56
 */

namespace Intex\OrgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="Intex\OrgBundle\Entity\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @JMS\ExclusionPolicy("all")
 *
 */
class User
{
    /**
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
     */
    protected $firstname;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     *
     * @JMS\Expose
     * @JMS\XmlAttribute
     */
    protected $lastname;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     *
     * @JMS\Expose
     * @JMS\XmlAttribute
     */
    protected $middlename;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     *
     * @JMS\Expose
     * @JMS\XmlAttribute
     */
    protected $bithday;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="bigint")
     *
     * @JMS\Expose
     * @JMS\XmlAttribute
     */
    protected $inn;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="bigint")
     *
     * @JMS\Expose
     * @JMS\XmlAttribute
     */
    protected $snils;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="users")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    protected $company;


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
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * @param mixed $middlename
     */
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;
    }

    /**
     * @return mixed
     */
    public function getBithday()
    {
        return $this->bithday;
    }

    /**
     * @param mixed $bithday
     */
    public function setBithday($bithday)
    {
        $this->bithday = $bithday;
    }

    /**
     * @return mixed
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * @param mixed $inn
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
    }

    /**
     * @return mixed
     */
    public function getSnils()
    {
        return $this->snils;
    }

    /**
     * @param mixed $snils
     */
    public function setSnils($snils)
    {
        $this->snils = $snils;
    }




    /**
     * Set company
     *
     * @param \Intex\OrgBundle\Entity\Company $company
     *
     * @return User
     */
    public function setCompany(\Intex\OrgBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Intex\OrgBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }
}
