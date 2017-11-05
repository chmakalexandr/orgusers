<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 11:42
 */

namespace Intex\OrgBundle\Controller;

use Intex\OrgBundle\Entity\Company;
use Intex\OrgBundle\Entity\Organizations;
use Intex\OrgBundle\Entity\User;
use JMS\Serializer\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('IntexOrgBundle:Page:index.html.twig');
    }

    public function uploadXmlAction()
    {
       $form = $this->createFormBuilder()
            ->add('file','file',array('label' => 'Load XML file',
                "attr" => array(
                    "accept" => ".xml",
                    "multiple" => "multiple",
                )
            ))
            ->getForm();

        return $this->render('IntexOrgBundle:Page:upload.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function loadUsersAction(Request $request)
    {
        $xmlFile=$request->files->get('form');
        $xmlData=file_get_contents($xmlFile['file']->getRealPath());
        $data=$this->get('jms_serializer')->deserialize($xmlData, 'Intex\OrgBundle\Entity\Organizations', 'xml');
        $em = $this->getDoctrine()
            ->getManager();
        $companies=$data->getCompanies();
        foreach ($companies as $organization) {
            try {
                $company = new Company();
                $company->setName($organization->getName());
                $company->setOgrn($organization->getOgrn());
                $company->setOktmo($organization->getOktmo());
                $em->persist($company);
                $em->flush();
                //$org=$em->getRepository('IntexOrgBundle:Company')->findOneBy(array('ogrn'=>$company->getOgrn()));
                $users=$organization->getUsers();
                foreach ($users as $human){
                    $user=New User();
                    $user->setCompany($company);
                    $user->setFirstname($human->getFirstName());
                    $user->setMiddlename($human->getMiddlename());
                    $user->setLastname($human->getLastName());
                    $user->setSnils($human->getSnils());
                    $user->setInn($human->getInn());
                    $dateBith=$human->getBithday();

                    $data= (array)$dateBith;
                    $dt=date('Y-m-d',strtotime($data['date']));
                    $user->setBithday($dt);
                    $em->persist($user);
                    $em->flush();
                }

            } catch (Exception $e) {
                $this->addFlash('notice',$e->getMessage());
            }
        }

        $this->addFlash('notice', 'Users successfully loaded');
        return $this->render('IntexOrgBundle:Page:index.html.twig', array(
            'users' => $data
        ));

    }
}