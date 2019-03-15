<?php
/**
 * Created by PhpStorm.
 * User: rsaillou2018
 * Date: 07/03/2019
 * Time: 09:57
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Idea;
use AppBundle\Form\IdeaType;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\ImageManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/idee", name="idee_")
 * Class IdeaController
 * @package AppBundle\Controller
 */
class IdeaController extends Controller
{
    /**
     *@Route("/", name="idees")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(){
        $em =$this->getDoctrine()->getManager();
        $repository =$em->getRepository(Idea::class);
        $ideas = $repository->getIdee();
        return $this->render("tp/idee.html.twig", ['ideas'=>$ideas]);
    }

    /**
     * @Route("/{ideeId}", name="detail", requirements={"ideeId":"\d+"}, methods={"GET","POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailAction($ideeId){
        $em =$this->getDoctrine()->getManager();
        $repository =$em->getRepository(Idea::class);

        /**
         * @var Idea $detailIdea
         */
        $idea = $repository->find($ideeId);

        if (empty($idea)){
            throw new $this->NotFoundHttpException("Idée introuvable ! ");
        }
        dump($idea);
        return $this->render("tp/detail.html.twig", ['idee'=>$idea]);
    }

    /**
     * @Route("/create", name="create")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request){
        //creation imposible a moins d'etre connecé
        //methode 1 : pour reguler l'accès
        //$this->denyAccessUnlessGranted("ROLE_USER");

        $idea = new Idea();

        //1ere option pour preremplir le champs author dans la creation d'une idée
        //if ($this->getUser()){
          //  $idea->setAuthor($this->getUser()->getPseudo());
        //}

        $form =$this->createForm( IdeaType::class, $idea);
        $idea->setDateCreated(new \DateTime('now'));
        $idea->setIsPublished(true);
        $idea->setPathImage('toto.jpg');

        //2eme option pour preremplir le champs author dans la creation d'une idée
        if (!empty($this->getUser())){
            $form->get('author')->setData($this->getUser()->getPseudo());
        }

        /*le remove permet de ne pas afficher les champs du formulaire sur la page web*/
        $form->remove('dateCreated');
        $form->remove('isPublished');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //Traiter l'image
            $em = $this->getDoctrine()->getManager();
            $em->persist($idea);
            $em->flush();


            /**
             * @var UploadedFile $image
             */
            $slugify = new Slugify();
            $filename = $slugify->slugify($idea->getTitle()."-".$idea->getId()).".jpg";

            $image = $idea->getPathImage();
            $image->move($this->get('kernel')->getProjectDir().'\web\images', $filename);

            $idea->setPathImage('/images/'.$filename);

            $em->persist($idea);
            $em->flush();
            $this->addFlash('success', 'Idea successfully added!');

            return $this->redirectToRoute('idee_detail', ['ideeId'=>$idea->getId()]);
        }
        return $this->render('tp/insert.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/update/{id}", name="update")
     * @param $ideeId
     */
    public function updateAction(Request $request, EntityManagerInterface $em, Idea $idea){

        $form =$this->createForm( IdeaType::class, $idea);
        /*le remove permet de ne pas afficher les champs du formulaire sur la page web*/
        $form->remove('dateCreated');
        $form->remove('isPublished');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //Traiter l'image
            $em = $this->getDoctrine()->getManager();
            $em->persist($idea);
            $em->flush();


            /**
             * @var UploadedFile $image
             */
            $slugify = new Slugify();
            $filename = $slugify->slugify($idea->getTitle()."-".$idea->getId()).".jpg";

            $image = $idea->getPathImage();
            $image->move($this->get('kernel')->getProjectDir().'\web\images', $filename);

            $idea->setPathImage('/images/'.$filename);


            $em->persist($idea);
            $em->flush();
            $this->addFlash('success', 'Idée modifiée avec succès!');

            return $this->redirectToRoute('idee_idees', ['id'=>$idea->getId()]);
        }
        return $this->render('tp/update.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/save/{idea}", name="save")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveAction(Request $request, EntityManagerInterface $em, Idea $idea=null){

        //Si l'idéa n'est pas injectée, c'est que je suis en création
        if (empty($idea)){
            $idea = new Idea();
            $idea->setDateCreated(new \DateTime('now'));
            $idea->setIsPublished(true);
            $idea->setPathImage('toto.jpg');

            $txt = "insérée";
            $view = "add";
        } else{
            $txt = 'modifiée';
            $view = 'edit';
        }

        $form =$this->createForm( IdeaType::class, $idea);
        /*le remove permet de ne pas afficher les champs du formulaire sur la page web*/
        $form->remove('dateCreated');
        $form->remove('isPublished');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($idea);
            $em->flush();
            $this->addFlash('success', 'Idea successfully added!');

            return $this->redirectToRoute('idee_detail', ['ideeId'=>$idea->getId()]);
        }
        return $this->render('tp/insert.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/delete/{idea}", name="delete")
     */
    public function deleteAction(Request $request, EntityManagerInterface $em, Idea $idea){
        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('ok', SubmitType::class);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        dump($form);
        if ($form->isSubmitted() ){
            $em->remove($idea);
            $em->flush();
            $this->addFlash('success', 'Idée supprimée avec succès!');
            return $this->redirectToRoute('idee_idees');
        }


        return $this->render('tp/delete.html.twig', ['form'=>$form->createView(), 'idee'=>$idea]  );
    }
}