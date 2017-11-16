<?php

namespace Intex\OrgBundle\Controller;

use Doctrine\DBAL\Driver\PDOException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intex\OrgBundle\Entity\User;
use Intex\OrgBundle\Entity\Company;
use Intex\OrgBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use XMLReader;


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

        return $this->render('IntexOrgBundle:User:index.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * Render information about user by id
     * @param int $userId
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
     * @param int $companyId
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
     * @param int $companyId
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
     * @param int $companyId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createUserAction(Request $request, $companyId)
    {
        $company = $this->getCompany($companyId);
        if (!$company) {
            throw $this->createNotFoundException('Unable to find company.');
        }
        $user = new User();
        $user->setCompany($company);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success',$this->get('translator')->trans('User was be added!'));
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

            if (($xmlFile['file']->getError())||(substr($xmlFile['file']->getClientOriginalName(),-4) != ".xml")){
                $this->addFlash('error', $this->get('translator')->trans('Wrong file'));
                return $this->render('IntexOrgBundle:User:upload.html.twig');
            }

            $xmlData = file_get_contents($xmlFile['file']->getRealPath());

            /*if (!simplexml_load_string($xmlData)|| mb_detect_encoding($xmlData)!='UTF-8' ) {
                $this->addFlash('error', $this->get('translator')->trans('Wrong XML file'));
                return $this->render('IntexOrgBundle:User:upload.html.twig');
            }
            */

            $data = $this->get('jms_serializer')->deserialize($xmlData, 'Intex\OrgBundle\Entity\Organizations', 'xml');
            $em = $this->getDoctrine()->getManager();
            $companies = $data->getCompanies();

            $existingCompanies = $em->getRepository('Intex\OrgBundle\Entity\Company')->getExistingCompanies($companies);

            $existingOgrns = array();
            foreach ($existingCompanies as $organization){
                $existingOgrns[] = $organization->getOgrn();
            }

            foreach ($companies as $organization){
                if (!in_array($organization->getOgrn(), $existingOgrns)) {
                    $company = new Company();
                    $company->setName($organization->getName());
                    $company->setOgrn($organization->getOgrn());
                    $company->setOktmo($organization->getOktmo());
                    $em->persist($company);
                } else {
                    $company = $this->getCompanyByOgrn($organization->getOgrn(), $existingCompanies);
                }

                $users = $organization->getUsers();
                if(!$em->getRepository('Intex\OrgBundle\Entity\User')->getExistingUsers($users)){
                    foreach ($users as $human) {
                        $user = New User();
                        $user->setCompany($company);
                        $user->setFirstname($human->getFirstName());
                        $user->setMiddlename($human->getMiddlename());
                        $user->setLastname($human->getLastName());
                        $user->setSnils($human->getSnils());
                        $user->setInn($human->getInn());
                        $user->setBithday($human->getBithday());
                        $em->persist($user);
                    }
                } else {
                    $this->addFlash('error', $this->get('translator')->trans('The file contains data about users who are already present in the database. Upload canceled.'));
                    return $this->render('IntexOrgBundle:User:upload.html.twig');
                }
            }
            $em->flush();
        } catch (\Exception $e) {
            $this->addFlash('error','Unnable add users in Db. Check XML file');
            return $this->render('IntexOrgBundle:User:upload.html.twig');
        }


        $this->addFlash('success', $this->get('translator')->trans('Users successfully loaded'));
        return $this->render('IntexOrgBundle:Page:index.html.twig');
    }

    /**
     * Renders form for upload users from XML file
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadXmlAction()
    {
        return $this->render('IntexOrgBundle:User:upload.html.twig');
    }

    /**
     * Shows the company in which the user belongs
     * @param int $companyId
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

    /**
     * Return company from array $companies in which the Primary State Registration Number = $ogrn
     * @param ArrayCollection $companies
     * @param int $ogrn
     * @return \Intex\OrgBundle\Entity\Company|null|object
     */
    protected function getCompanyByOgrn($ogrn, $companies)
    {
        $organization = null;
        foreach ($companies as $company){
            if ($company->getOgrn() == $ogrn){
                $organization = $company;
            }
        }
        return $organization;
    }
}
