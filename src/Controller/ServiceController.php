<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service/{name}",name="showService")
    */    
    public function showService($name):Response{
        echo "Service: $name";
        return $this->render('service/showService.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
    /**
     * @Route("/goToIndex",name="goToIndex")
    */ 
     public function goToIndex(){
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
