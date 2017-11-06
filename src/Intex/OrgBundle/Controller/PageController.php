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
                "attr" => array("accept" => ".xml",)))
           ->getForm();
       return $this->render('IntexOrgBundle:Page:upload.html.twig', array(
            'form' => $form->createView()
       ));
    }


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
                        $this->addFlash('error','В файле есть данные о пользователях, которые уже присутствуют в базе. Загрузка прекращена.');
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
}
