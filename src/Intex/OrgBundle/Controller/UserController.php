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
    public function listUsersAction()
    {
        $em = $this->getDoctrine()
            ->getManager();
        $users = $em->getRepository('IntexOrgBundle:User')
            ->findAll();

        if (!$users) {
            throw $this->createNotFoundException('Unable to find users.');
        }
        return $this->render('IntexOrgBundle:User:index.html.twig', array(
            'users' => $users
        ));
    }

    public function showUserAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('IntexOrgBundle:User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        return $this->render('IntexOrgBundle:User:show.html.twig', array(
            'user'      => $user,
        ));
    }

    public function listOrgUsersAction($companyId)
    {
        $company = $this->getCompany($companyId);
        $users=$company->getUsers();

        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }

        return $this->render('IntexOrgBundle:User:users.html.twig', array(
            'company'  => $company,
            'users'  => $users
        ));
    }

    public function newUserAction($companyId)
    {
        $company = $this->getCompany($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }
        $user = new User();
        $user->setCompany($company);
        $form = $this->createForm(UserType::class, $user);

        return $this->render('IntexOrgBundle:User:form.html.twig', array(
            'company' => $company,
            'form'   => $form->createView()
        ));
    }

    public function createUserAction(Request $request, $companyId)
    {
        $company = $this->getCompany($companyId);
        $user = new User();
        $user->setCompany($company);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','User was be added!');
            $users = $company->getUsers();
            return $this->redirect($this->generateUrl('intex_org_company_users',array('companyId'=>$companyId,'company'=>$company,'users'=>$users)));
        }

        return $this->render('IntexOrgBundle:User:form.html.twig', array(
            'company' => $company,
            'form' => $form->createView()
        ));
    }

    protected function getCompany($companyId)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('IntexOrgBundle:Company')->find($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }
        return $company;
    }
}
