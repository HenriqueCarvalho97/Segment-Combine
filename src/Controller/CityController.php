<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;
use Symfony\Component\HttpFoundation\Response;

class CityController extends AbstractController
{
    /**
     * @Route("apartamentos/cidades/{$slug}", name="rent")
     */
    public function rent($slug)
    {
        $repo = $this->getDoctrine()->getRepository(City::class);

        $city = $repo->findOneBy(array('name' => $slug));

        return new Response('Im gonna show ' . $city->getName());
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
}
