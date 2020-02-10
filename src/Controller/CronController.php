<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Controller;
use App\Entity\Task;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Status;

class CronController extends AbstractController{
    private $mailer;
    
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    /**
     * @Route("/cron", name="cron")
     */
    public function indexAction(){
        $entityManager = $this->getDoctrine()->getManager();
        $tasks = $entityManager->getRepository(Task::class)->findAllTasks();
        
        if($tasks){
            $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
            $id = 0;
            foreach($tasks as $each_record){
                if($yesterday == $each_record['deadline']->format('Y-m-d')){
                    $this->sendEmailDeadlinePassed($each_record);
                    $id = $each_record["id"];
                    //break;
                }
            }
        }
        
        $task = $entityManager->getRepository(Task::class)->find($id);
        $task->setStatus($entityManager->getReference(Status::class, 5));
        $entityManager->flush();
        dump("gata");
        exit();
    }
    
    private function sendEmailDeadlinePassed($data){
        $email = (new Email())
            ->from('ov.movila@gmail.com')
            ->to($data['email'])
            ->subject('Task Management - data limita depasita!')
            ->html('<p>Salut <strong>'.$data['name'].'</strong>! <br/>'
                    . 'Pentru taskul <strong>'.$data['task_name'].'</strong> data limita de implementare a fost depasita.(<strong>'.$data['deadline']->format('Y-m-d').'</strong>) '
                    . '</p>');
        $this->mailer->send($email);
    }
}