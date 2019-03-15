<?php
/**
 * Created by PhpStorm.
 * User: rsaillou2018
 * Date: 14/03/2019
 * Time: 13:31
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user", name="user_")
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $user->setRoles(['ROLE_USER']);
            $user->setSalt("145ddsdfl");

            $password =$passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Compte utilisateur créé avec succès');
            return $this->redirectToRoute('idee_idees');
        }
        return $this->render( 'user/register.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils){


        //obtenir la derniere erreur d'authentification
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig', ['error'=>$error, 'lastUsername'=>$lastUsername]);

    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(){

    }
}