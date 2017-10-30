<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 13:27
 */

namespace Intex\OrgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CompanyController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()
            ->getManager();
        $companies=$em->getRepository('IntexOrgBundle:Company')->getAllCompanies();
        return $this->render('IntexOrgBundle:Company:index.html.twig', array(
            'companies' => $companies
        ));
    }

    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $company = $em->getRepository('IntexOrgBundle:Company')->find($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        return $this->render('IntexOrgBundle:Company:show.html.twig', array(
            'company'      => $company,
        ));

    }

    public function listusersAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('IntexOrgBundle:Company')->find($id);
        $users=$company->getUsers();

        if (!$company) {
            throw $this->createNotFoundException('There are not company.');
        }

        return $this->render('IntexOrgBundle:Company:users.html.twig', array(
            'company'  => $company,
            'users'  => $users
        ));
    }



}