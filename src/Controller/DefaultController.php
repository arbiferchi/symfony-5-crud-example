<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="/")
     */
    public function index(): Response
    {
        /*$em = $this->getDoctrine()->getManager();
        $em->getConnection()->connect();
       dd($em->getConnection()->isConnected());*/

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("debug")
     */
    public function debug()
    {
        dd('foo');
    }
}
