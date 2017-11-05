<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.11.2017
 * Time: 9:05
 */
namespace Intex\OrgBundle\Entity;


use JMS\Serializer\Annotation as JMS;

/**
 * Class Organizations
 *
 * @JMS\XmlRoot("orgs")
 */
class Organizations
{
    /**
     *
     * @JMS\Type("ArrayCollection<Intex\OrgBundle\Entity\Company>")
     * @JMS\XmlList(inline=true, entry="org")
     */
    private $companies;

    /**
     * @return mixed
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param mixed $orgs
     */
    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }

}