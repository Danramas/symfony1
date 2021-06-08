<?php


namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     * @Route("/product/create", name="createProduct")
     */
    public function create(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $product = new Product();

        $product->setName('Asus');

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($product);
        $entityManager->flush();


        return new Response('Добавлен новый продукт: '.$product->getName());
    }

    /**
     * @Route("/product/{uuid}", name="showProduct")
     * @param $uuid
     * @return Response
     */
    public function show($uuid)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['uuid' => $uuid]);
        $product = $this->getDoctrine()->getRepository(Product::class)->find($uuid);

        if (!$product) {
            return $this->redirectToRoute('product');
        }

        return new Response($product->getName());
    }

    /**
     * @Route("/product/edit/{id}", name="updateProduct")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function update($id, EntityManagerInterface $entityManager)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'Категория не найдена'
            );
        }

        $product->setName('Auto');
        $entityManager->flush();

        return $this->redirectToRoute('showProduct', [
            'id' => $product->getId()
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="deleteProduct")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function delete($id, EntityManagerInterface $entityManager)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'Категория не найдена'
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('product', [

        ]);
    }

    /**
     * @Route("/product/add/manytomany", name="manytomany")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function manyToMany(EntityManagerInterface $entityManager)
    {
        $product = new Product();
        $product->setName('Fan');
        $entityManager->persist($product);

        $category = new Category();
        $category->setName('Cooling2');
        $category->setSlug('Cooling2');
        $category->addProducts($product);
        $entityManager->persist($category);


        $entityManager->flush();

        return new Response('Продукт '.$product->getUuid().' добавлен в категорию '.$category->getId());
    }
}