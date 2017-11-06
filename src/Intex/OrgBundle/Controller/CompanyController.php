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
    public function listCompaniesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('IntexOrgBundle:Company')->findAll();
        if (!$companies) {
            throw $this->createNotFoundException('Unable to find company.');
        }
        return $this->render('IntexOrgBundle:Company:index.html.twig', array(
            'companies' => $companies
        ));
    }

    public function showCompanyAction($companyId)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('IntexOrgBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }
        return $this->render('IntexOrgBundle:Company:show.html.twig', array(
            'company' => $company,
        ));
    }
}
