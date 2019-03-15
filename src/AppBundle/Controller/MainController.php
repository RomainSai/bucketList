<?php
/**
 * Created by PhpStorm.
 * User: rsaillou2018
 * Date: 06/03/2019
 * Time: 09:01
 */

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends Controller
{
    /**
     * @Route("/", name="layout")
     */
    public function layoutAction(){

        $prenom = "Romain";
        $nom = "Saillour";

        return $this->render('tp/layout.html.twig', ['prenom'=> $prenom, 'nom'=>$nom]);
    }

    /**
     * @Route("/secondPage", name="secondePage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function secondPageAction(){
        return $this->render('tp/block.html.twig');
    }

    /**
     * @Route("/aboutUs", name="aboutUs")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutUsAction(){
        return $this->render("tp/aboutUs.html.twig");
    }
}