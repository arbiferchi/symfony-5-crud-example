<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{











    /**
     * @Route("/test_1", name="test_1")
     */
    public function test_1(): Response
    {


        return new Response('<br>END TEST');
    }


    /**
     * @Route("/test_2", name="test_2")
     */
    public function test_2(): Response
    {

        return $this->render('<br>END TEST<br>');
    }





}
