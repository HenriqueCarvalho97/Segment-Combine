<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\City;

class BackendController extends AbstractController
{
    /**
     * @Route("/backend", name="backend")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getRepository(City::class);
        $cities = $em->findAll();
        return $this->render('backend/index.html.twig',
            array('cities'=>$cities));
    }

    /**
     * @Route("/backend/adicionar-cidade", name="new_city")
     */
    public function newCity(Request $request){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $city = new City();
        $city->setCountry("Portugal");
        $form = $this->createFormBuilder($city)
            ->add('name', TextType::class, array('label' => 'Cidade', 'required' => true, 'attr' => array("placeholder" => "Inserir Cidade")))
            ->add('country', CountryType::class, ["preferred_choices" => array('PT'),
                'label' => 'País',
                'required' => true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Criar Cidade'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $city = $form->getData();

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($city);
             $entityManager->flush();

            $this->addFlash('success', 'Cidade inserida com sucesso');

            return $this->redirectToRoute('backend');
        }

        return $this->render('backend/newcity.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/backend/alterar-cidade/{slug}", name="change_city")
     */
    public function changeCity(Request $request, $slug){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();

        $city = $entityManager->getRepository(City::class)->findOneBy(array("name"=>$slug));
        $form = $this->createFormBuilder($city)
            ->add('name', TextType::class, array('label' => 'Cidade', 'required' => true))
            ->add('country', CountryType::class, ["preferred_choices" => array('PT'),
                'label' => 'País',
                'required' => true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Alterar Cidade'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $city->setName($form->get('name')->getData());
            $city->setCountry($form->get('country')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            $this->addFlash('success', 'Cidade alterada com sucesso');

            return $this->redirectToRoute('backend');
        }

        return $this->render('backend/changecity.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/backend/adicionar-casa", name="new_house")
     */
    public function newHouse(Request $request){

    }
}
