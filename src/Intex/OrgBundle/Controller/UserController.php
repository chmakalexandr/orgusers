<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 12:45
 */

namespace Intex\OrgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intex\OrgBundle\Entity\User;
use Intex\OrgBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;


class UserController extends Controller
{
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('IntexOrgBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        return $this->render('IntexOrgBundle:User:show.html.twig', array(
            'user'      => $user,
        ));
    }

    public function newAction($id)
    {
        $company = $this->getCompany($id);

        $user = new User();
        $user->setCompany($company);
        $form = $this->createForm(UserType::class, $user);

        return $this->render('IntexOrgBundle:User:form.html.twig', array(
            'company' => $company,
            'form'   => $form->createView()
        ));
    }

    public function createAction(Request $request, $company_id)
    {
        $company = $this->getCompany($company_id);
        $user = new User();
        $user->setCompany($company);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('intex_org_company_users',array('id'=>$company->getId())));
        }

        return $this->render('IntexOrgBundle:User:form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    protected function getCompany($company_id)
    {
        $em = $this->getDoctrine()
            ->getManager();

        $company = $em->getRepository('IntexOrgBundle:Company')->find($company_id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        return $company;
    }

}