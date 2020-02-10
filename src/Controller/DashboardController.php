<?php

namespace App\Controller;
use App\Entity\Task;
use App\Form\Type\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(Request $request){
        $task = new Task();
        $user = $this->getUser();
        $form = $this->createForm(TaskType::class, $task);
        
        $entityManager = $this->getDoctrine()->getManager();
        $tasks = $entityManager->getRepository(Task::class)->findAllTasksByUser($user->getId());
        dump($tasks);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setUserId($user->getId());
            $task->setAddedOn(new \DateTime());
            
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
