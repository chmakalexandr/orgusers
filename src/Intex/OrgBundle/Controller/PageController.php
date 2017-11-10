<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 11:42
 */

namespace Intex\OrgBundle\Controller;

use Doctrine\DBAL\Types\DateType;
use Intex\OrgBundle\Entity\Company;
use Intex\OrgBundle\Entity\Organizations;
use Intex\OrgBundle\Entity\User;
use JMS\Serializer\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PageController
 * @package Intex\OrgBundle\Controller
 */
class PageController extends Controller
{
    /**
     * Render main page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {

        return $this->render('IntexOrgBundle:Page:index.html.twig');
    }

    /**
     * Render page for upload users from XML file
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadXmlAction()
    {
       $form = $this->createFormBuilder()
           ->add('file','file',array('label' => $this->get('translator')->trans('Load XML file'),
                "attr" => array("accept" => ".xml",)))
           ->getForm();
       return $this->render('IntexOrgBundle:Page:upload.html.twig', array(
            'form' => $form->createView()
       ));
    }
}
