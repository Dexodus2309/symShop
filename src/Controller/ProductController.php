<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product/list", name="listProduct")
     */
    public function getProductsAction()
    {
        $products = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();

        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/admin/product/edit/{id}", name="editProduct")
     */
    public function editProductAction(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $product->setName($data->getName());
            $product->setPrice($data->getPrice());

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('listProduct');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/product/remove/{id}", name="removeProduct")
     */
    public function removeProductAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('listProduct');
    }

    /**
     * @Route("/admin/product/create", name="createProduct")
     */
    public function createProductAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $product = new Product();

            $product->setName($data->getName());
            $product->setPrice($data->getPrice());

            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('listProduct');
        }

        return $this->render('product/edit.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}