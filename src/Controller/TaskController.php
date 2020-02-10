<?php

namespace App\Controller;

use App\Entity\Priority;
use App\Entity\User;
use App\Entity\Status;
use App\Entity\Task;
use \EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TaskController extends EasyAdminController{
    private $mailer;
    
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    
    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null){  
        $result = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
        
        $result->innerJoin("entity.user", 'user')
            ->andWhere('entity.user_id = :user')
            ->orWhere('user = :user AND entity.status != 1')
            ->setParameter('user', $this->getUser()->getId());
        
        return $result;
    }
    
    protected function persistEntity($entity){
        $this->updateAddedOn($entity);
        $this->updateUserId($entity);
        $this->sendEmail($entity);
        parent::persistEntity($entity);
    }
        
    protected function updateEntity($entity){
        $this->sendEmail($entity);
        parent::updateEntity($entity);
    }
    
    private function updateAddedOn($entity)
    {
        if (method_exists($entity, 'setAddedOn')) {
            $entity->setAddedOn(new \DateTime());
        }
    }
    
    private function updateUserId($entity){
        if (method_exists($entity, 'setUserId')) {
            $entity->setUserId($this->getUser()->getId());
        }
    }
    
    private function sendEmail($entity){
        $authorEntity = $entity->getUserId();
        $user = $this->getUser()->getId();
        
        if(($entity->getStatus()->getId() == 2) && ($authorEntity == $user)){
            //send email
            //php bin/console messenger:consume async -vv
            //https://symfony.com/doc/current/messenger.html#messenger-supervisor
            $users = $entity->getUser();
            if($users){
                foreach($users as $each_record){
                    $email = (new Email())
                        ->from('ov.movila@gmail.com')
                        ->to($each_record->getEmail())
                        ->subject('Task Management - nou task!')
                        ->html('<p>Salut <strong>'.$each_record->getName().'</strong>! <br/>'
                                . 'Ai fost adaugat cu succes la noul task: <br/>'
                                . '<strong>Nume:</strong> '.$entity->getName().'<br/>'
                                . '<strong>Descriere:</strong> '.$entity->getDescription().'<br/>'
                                . '<strong>Deadline:</strong> '.$entity->getDeadline()->format('Y-m-d').'<br/>'
                                . '<strong>Prioritate:</strong> '.$entity->getPriority()->getName().'<br/>'
                                . '<strong>Status:</strong> '.$entity->getStatus()->getName().'<br/>'
                                . '<strong>Adaugat de:</strong> '.$this->getUser()->getName().'<br/>'
                                . '</p>');
                    $this->mailer->send($email);
                }
            }
        }
    }
    
    public function createEntityFormBuilder($entity, $view)
    {
        $formBuilder = parent::createEntityFormBuilder($entity, $view);
        if($view == "new"){
            $entityManager = $this->getDoctrine()->getManager();
            $entity->setPriority($entityManager->getReference(Priority::class, 5));
            $entity->addUser($entityManager->getReference(User::class, $this->getUser()->getId()));
            $entity->setDeadline(new \DateTime());
        }else if($view == "edit"){
            $authorEntity = $entity->getUserId();
            $user = $this->getUser()->getId();
            if($authorEntity != $user){
                $formBuilder->add('name', TextType::class,[
                    'disabled' => true
                ]);
                
                $formBuilder->add('description', TextareaType::class,[
                    'disabled' => true
                ]);
                
                $formBuilder->add('priority', TextType::class,[
                    'disabled' => true
                ]);
                
                $formBuilder->add('user', EntityType::class,[
                    'class' => User::class,
                    'multiple' => true,
                    'disabled' => true
                ]);
                
                $formBuilder->add('deadline', DateType::class,[
                    'disabled' => true
                ]);
                
                $formBuilder->add('status', EntityType::class,[
                    'class' => Status::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->andWhere('s.id IN (:ids)')
                            ->orderBy('s.id', 'ASC')
                            ->setParameter('ids', array(2,3,5,6));
                    },
                ]);
            }
        }
        
        return $formBuilder;
    }
    
    protected function removeEntity($entity)
    {
        $authorEntity = $entity->getUserId();
        $user = $this->getUser()->getId();
        
        if($authorEntity != $user){
            $this->addFlash('error', 'Nu ai drepturi sa stergi acest task!');
            return $this->redirect('easyadmin');
        }
    }
}
