<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBaseController extends AbstractController
{
    public function renderDefault()
    {
        return ['title' => 'Мой первый проект на симфони (Админка)'];
    }
}