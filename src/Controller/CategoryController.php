<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category")
     */
    public function index()
    {
        $count = 1;

        return $this->render('category.html.twig', [
            'count' => $count,
        ]);
    }
}