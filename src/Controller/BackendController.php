<?php

namespace App\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\City;
use App\Entity\House;

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
            'form' => $form->createView(), 'city'=>$city,
        ));

    }

    /**
     * @Route("/backend/apagar-cidade/{id}", name="delete_city")
     */
    public function deleteCity($id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        $city = $entityManager->getRepository(City::class)->findOneBy(array('id' => $id));
        $houses = $entityManager->getRepository(House::class)->findOneBy(array('city' => $id));
        if($houses !== null){
            $this->addFlash('error', 'Existem casas nesta cidade, remova-as primeiro');
            return $this->redirectToRoute('backend');
        }
        $entityManager->remove($city);
        $entityManager->flush();
        $this->addFlash('success', 'Cidade removida com sucesso');
        return $this->redirectToRoute('backend');
    }


    /**
     * @Route("/backend/adicionar-casa", name="new_house")
     */
    public function newHouse(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $house = new House();
        $form = $this->createFormBuilder($house)
            ->add('name', TextType::class, array('label' => 'Nome', 'required' => true, 'attr' => array("placeholder" => "Nome da casa")))
            ->add('street', TextType::class, array('label' => 'Rua', 'required' => true, 'attr' => array("placeholder" => "Nome da rua")))
            ->add('location', TextType::class, array('label' => 'Location Iframe'))
            ->add('mainDescription', TextType::class, array('label' => 'Descrição da casa primária'))
            ->add('secondaryDescription', TextType::class, array('label' => 'Descrição da casa secundária'))
            ->add('city', EntityType::class, array('class' => City::class, 'choice_label' => 'name', 'label' => 'Cidade', 'required' => true))
            ->add('remodelationYear', DateType::class, array('label'=>'Ano de Remodelação', 'required'=>true))
            ->add('constructionYear', DateType::class, array('label'=>'Ano de Construção do Prédio', 'required'=>true))
            ->add('rooms', IntegerType::class, array('label'=>'Número de Quartos'))
            ->add('netarea', NumberType::class, array('label' => 'Área Útil'))
            ->add('grossarea', NumberType::class, array('label' => 'Área Bruta'))
            ->add('rent', ChoiceType::class, array(
                'choices'  => array(
                    'Alugar' => 1,
                    'Comprar' => 0
                ), 'label' => "Género de Casa"
                ))
            ->add('mainImage', FileType::class, array('mapped'=> false))
            ->add('save', SubmitType::class, array('label' => 'Criar Casa'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $house = $form->getData();

            $file = $form->get('mainImage')->getData();
            $fileDir = $this->getParameter('housemainimage_directory');
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move($fileDir,$fileName);
            $house->setMainImage($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($house);
            $entityManager->flush();

            $this->addFlash('success', 'Casa inserida com sucesso');

            return $this->redirectToRoute('backend');
        }

        return $this->render('backend/newhouse.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/backend/escolher-casa/{slug}", name="change_house_city")
     */
    public function showHousesCity(Request $request, $slug){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        $city = $entityManager->getRepository(City::class)->findOneBy(array("name"=>$slug));
        $houses = $entityManager->getRepository(House::class)->findBy(array("city"=>$city->getId()));
        $form = $this->createFormBuilder($houses)
            ->add('house', EntityType::class, array(
                'class' => House::class,
                'query_builder' => function (EntityRepository $er) use ($city){
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.city = :city')
                        ->setParameter('city', $city);
                },
                'choice_label' => 'name',
                'label' => 'Casa',
                'required' => true))
            ->add('save', SubmitType::class, array('label' => 'Escolher Casa'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $houseSlug = $form->get('house')->getData()->getSlug();

            return $this->redirectToRoute('change_house', array('city'=>$slug, 'house'=>$houseSlug));
        }
        return $this->render('backend/changehousecity.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/backend/alterar-casa/{city}/{house}", name="change_house")
     */
    public function changeHouse(Request $request, $city, $house){
        $entityManager = $this->getDoctrine()->getManager();

        $house = $entityManager->getRepository(House::class)->findOneBy(array("slug"=>$house));
        $form = $this->createFormBuilder($house)
            ->add('name', TextType::class, array('label' => 'Nome', 'required' => true, 'attr' => array("placeholder" => "Nome da casa")))
            ->add('street', TextType::class, array('label' => 'Rua', 'required' => true, 'attr' => array("placeholder" => "Nome da rua")))
            ->add('location', TextType::class, array('label' => 'Location Iframe'))
            ->add('mainDescription', TextType::class, array('label' => 'Descrição da casa primária'))
            ->add('secondaryDescription', TextType::class, array('label' => 'Descrição da casa secundária'))
            ->add('city', EntityType::class, array('class' => City::class, 'choice_label' => 'name', 'label' => 'Cidade', 'required' => true))
            ->add('remodelationYear', DateType::class, array('label'=>'Ano de Remodelação', 'required'=>true))
            ->add('constructionYear', DateType::class, array('label'=>'Ano de Construção do Prédio', 'required'=>true))
            ->add('rooms', IntegerType::class, array('label'=>'Número de Quartos'))
            ->add('netarea', NumberType::class, array('label' => 'Área Útil'))
            ->add('grossarea', NumberType::class, array('label' => 'Área Bruta'))
            ->add('rent', ChoiceType::class, array(
                'choices'  => array(
                    'Alugar' => 1,
                    'Comprar' => 0
                ), 'label' => "Género de Casa"
            ))
            ->add('mainImage', FileType::class, array('mapped'=> false))
            ->add('save', SubmitType::class, array('label' => 'Criar Casa'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('mainImage')->getData();
            $fileDir = $this->getParameter('housemainimage_directory');
            $fileName = $house->getMainImage();
            if(file_exists("$fileDir/$fileName")){
                $fileSystem = new Filesystem();
                $fileName2 = $fileDir . $fileName;
                $fileSystem->remove($fileName2);
            }
            $file->move($fileDir,$fileName);
            $house->setMainImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($house);
            $entityManager->flush();

            $this->addFlash('success', 'Cidade alterada com sucesso');

            return $this->redirectToRoute('backend');
        }

        return $this->render('backend/changehouse.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
