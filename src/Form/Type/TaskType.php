<?php

namespace App\Form\Type;
use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class TaskType extends AbstractType
{
    
    private $security;
    /*private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }*/
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options){
        $country = "";
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control mb-2'],
            ])
            ->add('deadline', DateType::class, [
                'attr' => ['class' => 'form-control mb-2'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control mb-2'],
            ])
            /*->add('executor', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'mapped' => false,
                'attr' => ['class' => 'form-control mb-2'],
                'multiple' => true,
                'expanded' => true,
                'data' => array($this->security->getUser())
                "mapped" => false,
                "multiple" => true,
                "attr" => array(
                    'class' => "form-control"
                ),
                'choices'  => array(
                    'Blogger' => 'ROLE_BLOGGER',
                    'Administrator' => 'ROLE_ADMIN'
                )
            ])*/
            ->add('usersTasks', EntityType::class, [
                'class' => User::class,
                //'mapped' => false,
                'multiple' => true,
                'required' => true,
                'label' => false,
                'attr' => ['class' => 'square_selectize selectize'],
                /*'constraints' => [
                    new Assert\NotBlank()
                ],*/
            ])
            ->add('priority', ChoiceType::class, [
                'attr' => ['class' => 'form-control mb-2'],
                'choices'  => [
                    '5' => 5,
                    '4' => 4,
                    '3' => 3,
                    '2' => 2,
                    '1' => 1
                ]
            ])
            ->add('status', ChoiceType::class, [
                'attr' => ['class' => 'form-control mb-2'],
                'choices'  => [
                    'DRAFT' => 1,
                    'PENDING' => 2,
                    'IN_PROGRESS' => 3,
                    'DONE' => 5,
                    'DEADLINE_PASSED' => 5,
                    'CANCELLED' => 6
                ]
            ])
            /*->add('added_on', DateType::class, [
                'attr' => ['class' => 'form-control mb-2'],
            ])*/
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg btn-primary',
                    'label' => 'Creare Task'
                ]
            ])
        ;
    }
    
    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver) {
        //parent::configureOptions($resolver);
    }
}

