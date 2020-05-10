<?php


namespace App\Controller\Main;

use Symfony\Component\Routing\Annotation\Route;

class AboutController extends BaseController
{
    /**
     * @Route("/about", name="about")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        return $this->render('about/about.html.twig', $forRender);
    }
}