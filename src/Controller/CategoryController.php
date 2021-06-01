<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryController
{
    /**
     * @Route("/category")
     */
    public function index()
    {
        return new Response('SCP Сосааать');
    }
}