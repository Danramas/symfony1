<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Category;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category")
     */
    public function index()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $count = [];

        foreach ($categories as $category)
        {
            $products = $category->getProduct();
            $count += [
                $category->getName() => count($products)
            ];
        }

        return $this->render('index.html.twig', [
            'categories' => $categories,
            'count' => $count
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
     * @Route("/category/add", name="addCategory")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('addCategory');
        }
        return $this->render('categoryForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{slug}", name="showCategory")
     * @param $slug
     * @return Response
     */
    public function show($slug)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(["slug" => $slug]);
        $allProducts = $category->getProduct();

        $products = [];

        foreach ($allProducts as $product)
        {
            $fileName_tmp = $product->getPreviewPicture();
            $fileName = $_SERVER['DOCUMENT_ROOT'].'uploads\previewPictures\\'.$fileName_tmp;

            $product->setPreviewPicture($fileName);

            if($product->getEnabled() == true){
                array_push($products, $product);
            }
        }

        if (!$category) {
            return $this->redirectToRoute('category');
        }

        return $this->render('category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
       // return new Response($category->getName());
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