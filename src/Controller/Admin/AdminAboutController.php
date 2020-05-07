<?php


namespace App\Controller\Admin;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAboutController extends AdminBaseController
{
    /**
     * @Route("/admin/about", name="admin_about")
     * @return Response
     */
    public function about()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'О проекте (Админка)';
        return $this->render('admin/about/about.html.twig', $forRender);
    }
}