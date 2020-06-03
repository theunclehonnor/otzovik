<?php

namespace App\Controller\Product;

use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use phpDocumentor\Reflection\Types\Integer;
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
        $allProducts = $productRepository->findBy(['is_published' => Product::PUBLISHED], ['create_at' =>'DESC']);
        foreach ($allProducts as $product) {
            $product->setAvarageEstimate($this->generateTotal($product->getId()));
        }
        return $this->render('index.html.twig', [
            'products' => $allProducts
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
     * @Route("product/{id}", name="product_show", methods={"GET", "POST"})
     */
    public function show(Request $request): Response
    {
        $comment = new Comment();
        $product = $this->getDoctrine()->getRepository(Product::class)->find($request->get('id'));
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $product->getId()], ['create_at' =>'ASC']);
        $product->setAvarageEstimate($this->generateTotal($product->getId()));
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreateAtValue();
            $comment->setUser($this->getUser());
            $comment->setProduct($product);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirect('/product/' . $product->getId());
        }
//        $conn = $this->getDoctrine()->getConnection();
//        $stmt = $conn->prepare('SELECT c.id, c.product_id, c.text, c.create_at, c._user_id   FROM product p INNER JOIN comment c ON c.product_id = p.id WHERE p.id = :id');
//        $stmt->execute(['id' => $product->getId()]);


        return $this->render('product/show.html.twig', [
            'product' => $product,
            'comments' => $comments,
            'commentForm' => $form->createView()
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
    // Средняя оценка
    private function generateTotal($idProduct): float
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['product' => $idProduct], ['create_at' =>'ASC']);
        $averageEstimate = 0;
        if($comments) {
            foreach ($comments as $comment) {
                $averageEstimate = $averageEstimate + $comment->getEstimate();
            }
            $averageEstimate = round($averageEstimate / count($comments) , 2);
        } else
            $averageEstimate = 0;
        return $averageEstimate;
    }

//    /**
//     * @Route("product/{id}/edit", name="product_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, Product $product): Response
//    {
//        $form = $this->createForm(ProductType::class, $product);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $product->setCreateAtValue();
//            $product->setUpdateAtValue();
//            //image
//            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
//            $file = $form->get('image')->getData();
//            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
//            // перемещает файл в каталог, где хранятся брошюры
//            $file->move(
//                $this->getParameter('products_directory'),
//                $fileName
//            );
//            $product->setImage($fileName);
//
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('product_index');
//        }
//
//        return $this->render('product/edit.html.twig', [
//            'product' => $product,
//            'productForm' => $form->createView(),
//        ]);
//    }

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
