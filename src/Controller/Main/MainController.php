<?php


namespace App\Controller\Main;

use Symfony\Component\Routing\Annotation\Route;

class MainController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        return $this->render('index.html.twig', $forRender);
    }
}