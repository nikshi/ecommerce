<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\CategoryType;
use AppBundle\Form\ProductPromotionType;
use Cocur\Slugify\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 * @Security(expression="has_role('ROLE_EDITOR')")

 */

class CategoryController extends Controller
{

    private $limit_per_page = 10;

    /**
     * @Route("/categories", name="categories_list")
     * @Method("GET")
     */
    public function categoriesAction(Request $request)
    {

        $query = $this->getDoctrine()->getRepository('AppBundle:Category')->fetchAllCategories();

        $paginator  = $this->get('knp_paginator');
        $categories = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/, $this->limit_per_page/*limit per page*/
        );

        return $this->render('categories/list.html.twig', ['categories' => $categories]);
    }


    /**
     * @Route("/category/add", name="add_category")
     * @Method("GET")
     */
    public function addCategoryAction()
    {
        $categoryForm = $this->createForm(CategoryType::class);
        return $this->render('categories/addcategory.html.twig', ['categoryForm' => $categoryForm->createView()]);
    }

    /**
     * @Route("/category/add")
     * @Method("POST")
     */
    public function addCategoryProcess(Request $request)
    {

        $category = new Category();
        $slugify = new Slugify();

        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            $slug = $slugify->slugify($category->getName());

            $category->setSlug($slug);

            /** @var UploadedFile $file */
            $file = $category->getImageForm();
            if($file){
                $filename = md5($category->getName() . '' . date("now")) . '.' . $file->getClientOriginalExtension();
                $category->setImage($filename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            if($file){
                $file->move($this->get('kernel')->getRootDir() . '/../web/images/categories/' . $category->getId(), $filename);
            }
            $this->addFlash('success', "Продуктът е добавен успешно (" . $category->getId() . ")");

            return $this->redirectToRoute('categories_list');
        }else {
            return $this->render('categories/addcategory.html.twig', ['categoryForm'=> $categoryForm->createView()]);
        }

    }

    /**
     * @Route("admin/category/delete", name="delete_category")
     * @Method("POST")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security(expression="has_role('ROLE_EDITOR')")
     */

    public function deleteProcess(Request $request)
    {

        $em     = $this->getDoctrine();
        $category   = $em->getRepository('AppBundle:Category')->find($request->get('id'));

        if($category){
            $fs = new Filesystem();
            $imageDir = $this->get('kernel')->getRootDir().'/../web/images/categories/'.$category->getId();
            $fs->remove($imageDir);

            $em = $em->getManager();
            $em->remove($category);
            $em->flush();

            $this->addFlash('success', "Категорията е успешно изтрита");
            return $this->redirectToRoute("categories_list");
        }else {
            $this->addFlash('error', "Грешка! Продуктът не е намерен");
            return $this->redirectToRoute("categories_list");
        }
    }


    /**
     * @Route("/category/edit/{id}", name="edit_category")
     * @Method("GET")
     * @Security(expression="has_role('ROLE_EDITOR')")
     */
    public function editAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found with id: '.$id
            );
        }

        $categoryForm = $this->createForm(CategoryType::class, $category );

        $promotion = $em->getRepository('AppBundle:Promotion')->findOneBy(['category' => $category]);

        $promotionForm = $this->createForm(ProductPromotionType::class, $promotion, array(
            'action' => $this->generateUrl('save_category_promotion', array('id' => $category->getId())
            )));

        return $this->render('categories/editcategory.html.twig', ['categoryForm'=> $categoryForm->createView(), 'promotionForm' => $promotionForm->createView(), 'category' => $category ]);
    }


    /**
     * @Route("/category/edit/{id}")
     * @Method("POST")
     * @Security(expression="has_role('ROLE_EDITOR')")
     */

    public function editProcess(Request $request, $id = null)
    {

        $em             = $this->getDoctrine();
        $category        = $em->getRepository('AppBundle:Category')->find($id);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found with id: '.$id
            );
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($category->getImageForm() instanceof UploadedFile) {
                $filePath = $this->get('kernel')->getRootDir() . '/../web/images/categories/' . $category->getId();
                /** @var UploadedFile $file */
                $file = $category->getImageForm();
                $filename = md5($category->getName() . '' . date("now")) . '.' . $file->getClientOriginalExtension();
                foreach(glob($filePath.'*.*') as $v){
                    unlink($v);
                }
                $file->move($filePath, $filename);
                $category->setImage($filename);
            } else {
                $category->setImage($category->getImage());
            }

            $em = $em->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', "Категорията е успешно редактирана");

            return $this->redirectToRoute("edit_category", array('id'=>$category->getId()));
        }else {
            $this->addFlash('error', "Грешка!");
            return $this->render('products/edit.html.twig', ['form'=> $form->createView()]);
        }
    }

}
