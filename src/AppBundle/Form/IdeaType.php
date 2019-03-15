<?php

namespace AppBundle\Form;


use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class IdeaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new Date();

        $builder
            ->add('title', TextType::class, ['label' => 'Titre :'])
            ->add('description', TextareaType::class)
            ->add('author', TextType::class, ['label'=> "auteur"])
            ->add('isPublished', ChoiceType::class, array('choices' => array('Publié' =>1, 'En cours' =>2)))
            ->add('dateCreated', DateType::class, array(
                'required' => false,
                'years' => range( date('Y'), 1900 ),
                'label' => 'Date de publication'))
            ->add('pathImage', FileType::class, array('data_class' => null, 'label'=>'Image'))
            ->add('categories', EntityType::class, ['class'=>Category::class,
                'label'=>'Catégorie',
                'placeholder'=>'Veuillez renseigner une catégorie'])

            //->add('categories', EntityType::class, ['class'=> Category::class,
              //  'choice_label'=>'name',
                //'label'=> 'Catégorie'])
            ->add('valider', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Idea'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_idea';
    }


}
