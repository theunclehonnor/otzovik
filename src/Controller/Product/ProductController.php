<?php

namespace App\Controller\Product;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('index.html.twig', [
            'products' => $productRepository->findBy(['is_published' => Product::PUBLISHED])
        ]);
    }

    /**
     * @Route("product/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreateAtValue();
            $product->setUpdateAtValue();
            $product->setDraft();
            //image
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('image')->getData();
            if($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                // перемещает файл в каталог
                $file->move(
                    $this->getParameter('products_directory'),
                    $fileName
                );
                $product->setImage($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'productForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("product/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() уменьшает схожесть имён файлов, сгенерированных
        // uniqid(), которые основанный на временных отметках
        return md5(uniqid());
    }

    /**
     * @Route("product/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreateAtValue();
            $product->setUpdateAtValue();
            //image
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('image')->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            // перемещает файл в каталог, где хранятся брошюры
            $file->move(
                $this->getParameter('products_directory'),
                $fileName
            );
            $product->setImage($fileName);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'productForm' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("product/{id}", name="product_delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, Product $product): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            // удаляем файл
//            $filesystem = new Filesystem();
//            $filesystem->remove('assets/main/img/uploads/' . $product->getImage());
//
//            $entityManager->remove($product);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('product_index');
//    }
}
