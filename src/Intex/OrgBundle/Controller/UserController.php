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
    public function indexAction()
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

    public function showUserAction($user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('IntexOrgBundle:User')->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find user.');
        }

        return $this->render('IntexOrgBundle:User:show.html.twig', array(
            'user'      => $user,
        ));
    }

    public function listUsersAction($company_id)
    {
        $company = $this->getCompany($company_id);
        $users=$company->getUsers();

        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }
        if (!$users) {
            throw $this->createNotFoundException('There are not users in this company.');
        }

        return $this->render('IntexOrgBundle:User:users.html.twig', array(
            'company'  => $company,
            'users'  => $users
        ));
    }

    public function newUserAction($company_id)
    {
        $company = $this->getCompany($company_id);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }
        $user = new User();
        $user->setCompany($company);
        $form = $this->createForm(UserType::class, $user);

        return $this->render('IntexOrgBundle:User:form.html.twig', array(
            'company_id' => $company_id,
            'form'   => $form->createView()
        ));
    }

    public function createUserAction(Request $request, $company_id)
    {
        $company = $this->getCompany($company_id);
        $user = new User();
        $user->setCompany($company);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice','User was be added!');
            return $this->redirect($this->generateUrl('intex_org_company_users',array('company_id'=>$company_id)));
        }

        return $this->render('IntexOrgBundle:User:form.html.twig', array(
            'company_id' => $company_id,
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