<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('category.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     * @Route("/category/create", name="createCategory")
     */
    public function create(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $category = new Category();

        $category->setName('Computers');
        $category->setSlug('Computers');

        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($category);
        $entityManager->flush();


        return new Response('Создана новая категория: '.$category->getName());
    }

    /**
     * @Route("/category/{slug}", name="showCategory")
     * @param $slug
     * @return Response
     */
    public function show($slug)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(["slug" => $slug]);

        if (!$category) {
            return $this->redirectToRoute('category');
        }

        return new Response($category->getName());
    }

    /**
     * @Route("/category/{id}", name="showCategory")
     * @param $id
     * @return Response
     */
    public function showById($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->redirectToRoute('category');
        }

        return new Response($category->getName());
    }

    /**
     * @Route("/category/edit/{id}", name="updateCategory")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function update($id, EntityManagerInterface $entityManager)
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'Категория не найдена'
            );
        }

        $category->setName('Auto');
        $entityManager->flush();

        return $this->redirectToRoute('showCategory', [
            'id' => $category->getId()
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="deleteCategory")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function delete($id, EntityManagerInterface $entityManager)
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'Категория не найдена'
            );
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('category', [

        ]);
    }
}