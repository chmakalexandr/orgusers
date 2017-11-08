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
use Intex\OrgBundle\Entity\Company;
use Intex\OrgBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class UserController
 * @package Intex\OrgBundle\Controller
 */
class UserController extends Controller
{

    /**
     * Render list all users
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Render information about user by id
     * @param $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Renders list company users
     * @param $companyId
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Renders form for add user to company
     * @param $companyId
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Add user in DB
     * @param Request $request
     * @param $companyId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Load users with companies from XML file
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadUsersAction(Request $request)
    {
        try {
            $xmlFile = $request->files->get('form');
            $xmlData = file_get_contents($xmlFile['file']->getRealPath());
            $data = $this->get('jms_serializer')->deserialize($xmlData, 'Intex\OrgBundle\Entity\Organizations', 'xml');
            $em = $this->getDoctrine()->getManager();
            $companies = $data->getCompanies();
            foreach ($companies as $organization) {
                if ($em->getRepository('Intex\OrgBundle\Entity\Company')->isUniqueOrganization($organization)){
                    $company = new Company();
                    $company->setName($organization->getName());
                    $company->setOgrn($organization->getOgrn());
                    $company->setOktmo($organization->getOktmo());
                    $em->persist($company);
                } else {
                    $company = $em->getRepository('Intex\OrgBundle\Entity\Company')->findOneBy(array("ogrn"=>$organization->getOgrn()));
                }
                $users = $organization->getUsers();
                foreach ($users as $human) {
                    if ($em->getRepository('Intex\OrgBundle\Entity\User')->isUniqueUser($human)){
                        $user = New User();
                        $user->setCompany($company);
                        $user->setFirstname($human->getFirstName());
                        $user->setMiddlename($human->getMiddlename());
                        $user->setLastname($human->getLastName());
                        $user->setSnils($human->getSnils());
                        $user->setInn($human->getInn());
                        $user->setBithday( $human->getBithday());
                        $em->persist($user);
                    } else {
                        $this->addFlash('error','The file contains data about users who are already present in the database. Upload canceled.');
                        return $this->render('IntexOrgBundle:Page:index.html.twig');
                    }
                }
            }
        } catch (Exception $e) {
            $this->addFlash('error',$e->getMessage());
            return $this->render('IntexOrgBundle:Page:index.html.twig');
        }

        $em->flush();
        $this->addFlash('success', 'Users successfully loaded');
        return $this->render('IntexOrgBundle:Page:index.html.twig');
    }

    /**
     * Shows the company in which the user belongs
     * @param $companyId
     * @return \Intex\OrgBundle\Entity\Company|null|object
     */
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
