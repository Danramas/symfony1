<?php


namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/add", name="addProduct")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $product = new Product();

        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('previewPicture', FileType::class, array('required' => false))
            ->add('enabled', CheckboxType::class, array('required' => false))
            ->add('category', CollectionType::class, [
                'entry_type'   => ChoiceType::class,
                'entry_options'  => [
                    //'multiple' => true,
                    'choices'  => $categories
                    ],
                    'data' => [
                        $categories
                    ],


                ]
            )
            ->add('save', SubmitType::class, array('label' => 'Добавить продукт'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('previewPicture')->getData();

            $product = $form->getData();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('previewPictureDirectory'),
                $fileName
            );

            $product->setPreviewPicture($fileName);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('addProduct');
        }
        return $this->render('productForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{slug}/{uuid}", name="showProduct")
     * @param $uuid
     * @param $slug
     * @return Response
     */
    public function show($uuid, $slug)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($uuid);

        $fileName_tmp = $product->getPreviewPicture();
        $fileName = $_SERVER['DOCUMENT_ROOT'].'uploads\previewPictures\\'.$fileName_tmp;
        $product->setPreviewPicture($fileName);

        if (!$product || $product->getEnabled() == false) {
            return $this->redirectToRoute('category');
        }

        return $this->render('product.html.twig', [
            'product' => $product,
        ]);
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
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}