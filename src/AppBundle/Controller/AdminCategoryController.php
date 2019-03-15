<?php
/**
 * Created by PhpStorm.
 * User: rsaillou2018
 * Date: 13/03/2019
 * Time: 11:20
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 * @Route("/admin/category", name="admin_category_")
 */
class AdminCategoryController extends Controller
{
    /**
     * @Route("/", name="categories")
     * @param EntityManagerInterface $em
     */
    public function listAction(EntityManagerInterface $em){
        $repository = $em->getRepository(Category::class);
        $categories = $repository->getCategory();
        return $this->render('tp/listCategory.html.twig', ['categories'=>$categories]);

    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     */
    public function createAction(Request $request, EntityManagerInterface $em)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category_categories');
        }
        return $this->render('tp/insertCategory.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @Route("/update/{id}", name="updateCategory")
     * @param EntityManagerInterface $em
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request,EntityManagerInterface $em, Category $category){
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès!');

            return $this->redirectToRoute('admin_category_categories');
        }
        return $this->render('tp/updateCategory.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Category $category
     * @Route("/delete/{id}", name="deleteCategory")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, EntityManagerInterface $em, Category $category){
        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('OK', SubmitType::class);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em->remove($category);
            try{
                $em->flush();
                $this->addFlash('success', 'Catégorie supprimée avec succès');

            } catch (ForeignKeyConstraintViolationException $e){
                $this->addFlash("error", "Erreur lors de la suppression, vous devez la vider des idées");
                $this->redirectToRoute("admin_category_categories");
            }
            return $this->redirectToRoute('admin_category_categories');

        }
        return $this->render('tp/deleteCategory.html.twig', ['form'=>$form->createView(), 'category'=>$category]);
    }

}