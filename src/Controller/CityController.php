<?php

namespace App\Controller;

use App\Entity\House;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CityController extends AbstractController
{
    /**
     * @Route("apartamentos/cidades/{$slug}", name="rent")
     */
    public function rent($slug)
    {
        $houses = $this->getHouses(1, $slug);

        return $this->render('search/index.html.twig', array('houses' => $houses, "city" => $slug));
    }

    /**
     * @Route("imoveis/cidades/{$slug}", name="buy")
     */
    public function buy($slug)
    {
        $repo = $this->getDoctrine()->getRepository(City::class);

        $city = $repo->findOneBy(array('name' => $slug));

        return new Response('Im gonna show ' . $city->getName());
    }

    public function getCities(){
        $repo = $this->getDoctrine()->getRepository(City::class);
        $cities = $repo->findAll();
        return $cities;
    }

    public function getHouses($rent, $city){
        $cityRepo = $this->getDoctrine()->getRepository(City::class);
        $houseRepo = $this->getDoctrine()->getRepository(House::class);
        $city = $cityRepo->findOneBy(array('name' => $city));
        $houses = $houseRepo->findBy(array('city' => $city->getId(), 'rent' => $rent));
        return $houses;
    }
}
