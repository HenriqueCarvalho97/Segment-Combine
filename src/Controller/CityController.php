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
     * @Route("{$type}/cidades/{$slug}", name="houseSearch")
     */
    public function house($type, $slug)
    {
        switch ($type){
            case "apartamentos":
                $rent = 1;
                $typeNameSearch = "Imóveis";
                $typeSearch = "imoveis";
                $typeName = "Apartamentos";
                break;
            case "imoveis":
                $rent = 0;
                $typeNameSearch = "Apartamentos";
                $typeSearch = "apartamentos";
                $typeName = "Imóveis";
                break;
            default:
                $rent = 1;
        }
        $houses = $this->getHouses($rent, $slug);

        return $this->render('search/index.html.twig', array('houses' => $houses, "city" => $slug,
            "typeNameSearch" => $typeNameSearch, "typeSearch" => $typeSearch, "cities" => $this->getCities(),
            "type" => $type, "typeName" => $typeName));
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
