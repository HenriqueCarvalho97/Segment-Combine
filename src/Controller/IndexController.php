<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\City;

class IndexController extends AbstractController
{
    /**
     * @Route({
     *     "pt": "/",
     *     "en": "/en"
     *     }, name="homepage")
     */
    public function index()
    {
        $cities = $this->getCities();

        return $this->render('index/index.html.twig', array('cities' => $cities));
    }

    public function getCities(){
        $repo = $this->getDoctrine()->getRepository(City::class);
        $cities = $repo->findAll();
        return $cities;
    }
}
