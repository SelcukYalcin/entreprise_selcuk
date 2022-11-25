<?php

namespace App\Controller;

use doctrine;
use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="app_employe")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $employes = $doctrine->getRepository(Employe::class)->findAll();

        return $this->render('employe/index.html.twig', [
            'employes' => $employes

        ]);
    }


    /**
     * @Route("/employe/add", name="add_employe")
     */

    public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $employe = $form->getData();
            $entityManager = $doctrine->getManager();
            //prepare
            $entityManager->persist($employe);
            //insert into (execute)
            $entityManager->flush();

            return $this->redirectToRoute('app_employe');
        }

        // Vue pour afficher le formulaire d'ajout
        return $this->render('employe/add.html.twig', [
            'formAddEmploye' => $form->createView()
        ]);
    }
    /**
     * @Route("/employe/{id}", name="show_employe")
     */
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe

        ]);
    }
}
