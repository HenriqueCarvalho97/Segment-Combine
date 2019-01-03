<?php

namespace App\Controller;

use App\Entity\House;
use App\Entity\City;
use App\Entity\HouseImage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HouseController extends AbstractController
{
    /**
     * @Route("/{type}/cidades/{slug}/{housename}", name="house")
     */
    public function index($type, $slug, $housename)
    {
        switch ($type){
            case "apartamentos":
                $rent = 1;
                $typeName= "Apartamentos";
                $typeSearch = "imoveis";
                break;
            case "imoveis":
                $rent = 0;
                $typeName = "Imóveis";
                $typeSearch = "apartamentos";
                break;
            default:
                $rent = 1;
        }
        $house = $this->findHouse($rent, $slug, $housename);
        $images = $this->getDoctrine()->getRepository(HouseImage::class)->findBy(array("house"=>$house->getId()));
        return $this->render('house/index.html.twig', [
            'controller_name' => 'HouseController',
            'house' => $house,
            'city' => $slug,
            'type' => $typeName,
            'images' => $images
        ]);
    }

    public function findHouse($rent, $city, $housename){
        $houserepo = $this->getDoctrine()->getRepository(House::class);
        $cityrepo = $this->getDoctrine()->getRepository(City::class);
        $cityId = $cityrepo->findOneBy(array("name"=>$city))->getId();
        $house = $houserepo->findOneBy(array("rent"=>$rent, "city"=>$cityId, "slug"=>$housename));
        return $house;
    }
}
