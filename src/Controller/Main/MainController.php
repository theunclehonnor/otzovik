<?php


namespace App\Controller\Main;

use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends BaseController
{
//    /**
//     * @Route("/", name="index")
//     */
//    public function index()
//    {
//        $product = $this->getDoctrine()->getRepository(Product::class)->findAll();
//
//        $forRender = parent::renderDefault();
//        $forRender['product'] = $product;
//        return $this->render('index.html.twig', $forRender);
//    }
}