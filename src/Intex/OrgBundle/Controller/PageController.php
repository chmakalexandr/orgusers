<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.10.2017
 * Time: 11:42
 */

namespace Intex\OrgBundle\Controller;

use Intex\OrgBundle\Entity\Company;
use Intex\OrgBundle\Entity\Document;
use Intex\OrgBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('IntexOrgBundle:Page:index.html.twig');
    }

    public function uploadAction()
    {
      /*  $form = $this->createFormBuilder()
            ->add('file','file',array('label' => 'Load XML file',
                "attr" => array(
                    "accept" => ".xml",
                    "multiple" => "multiple",

                )
            ))
            ->getForm();
      */
        $doc = new Document();
        $form = $this->createForm(DocumentType::class, $doc);
        return $this->render('IntexOrgBundle:Page:upload.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function loadAction(Request $request)
    {
        /*$encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        */
        //$data=$request->files->get('form');

        $serializer = $this->get('jms_serializer');

        $doc = new Document();

        $form = $this->createForm(DocumentType::class, $doc);

        $form->handleRequest($request);

        //$data=$doc->getFile();

        //$xml=file_get_contents ($data->getRealPath());

        $xml=<<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<orgs>
	<org displayName="ООО Краснознаменск" ogrn="9697567955367" oktmo="34586905567">
		<user firstname="Василий" middlename="Иванович" lastname="Пупкин" inn="8947493759347894" snils="9762738648233" bithday="1980-08-08" />
		<user firstname="Виталий" middlename="Петрович" lastname="Швабрин" inn="8947493345457894" snils="9762345358233"  bithday="1988-05-18" />
	</org>
	<org displayName="ООО Серп и молот" ogrn="9693453534747" oktmo="34585645567">
		<user firstname="Александр" middlename="Сергеевич" lastname="Пушкин" inn="8947345345354894" snils="7667877898233" bithday="1995-06-18" />
	</org>
</orgs>
EOT;
        //$data=$request->files->get('form');

        $users=$serializer->deserialize($xml, 'Intex\OrgBundle\Entity\Company', 'xml');
        //$users = $serializer->deserialize($xml, Company::class, 'xml');
        return $this->render('IntexOrgBundle:Page:index.html.twig', array(
            'users' => $users
        ));

    }
}