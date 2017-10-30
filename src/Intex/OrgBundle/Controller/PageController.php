<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 11:42
 */

namespace Intex\OrgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()
            ->getManager();

        $users = $em->getRepository('IntexOrgBundle:User')
            ->getAllUsers();

        return $this->render('IntexOrgBundle:Page:index.html.twig', array(
            'users' => $users
        ));
    }
}