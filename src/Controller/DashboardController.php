<?php

namespace App\Controller;
use App\Entity\Task;
use App\Entity\User;
use App\Entity\UsersTask;
use App\Form\Type\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request){
        $task = new Task();
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            /*var_dump($form->getData());
            exit();*/
            $task->setUser($user);
            $task->setAddedOn(new \DateTime());
            
            //$executor = $form->get("executor")->getData();
            
            /*$executor = $form->get('executor')->getData();
            if($executor){
                foreach($executor as $each_record){
                    var_dump($each_record);
                    exit();
                }
            }*/
            /*$usersTask = new UsersTask();
            $usersTask->setTask($task);
            $usersTask->setUser($user);*/
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            
            $this->addFlash('success', 'Task Created!');
            return $this->redirect($request->getUri());
        }
        
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'form' => $form->createView()
        ]);
    }
}
