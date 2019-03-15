<?php
/**
 * Created by PhpStorm.
 * User: rsaillou2018
 * Date: 13/03/2019
 * Time: 16:17
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @Route("/admin/", name="")
 * @package AppBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="list")
     */
    public function listAction(EntityManagerInterface $em){
        $repo = $em->getRepository(Category::class);
        $listCategories = $repo->findAll();

        return $this->render("tp/listCategory.html.twig", ["categories"=>$listCategories]);
    }

    /**
     * @Route("/view/{category}", name="")
     */
    public function viewAction(){

    }
}