<?php

namespace App\Controller;

use App\Entity\Emprunteur;
use App\Form\EmprunteurType;
use App\Repository\EmprunteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/emprunteur')]
class EmprunteurController extends AbstractController
{
    #[Route('/', name: 'app_emprunteur_index', methods: ['GET'])]
    public function index(EmprunteurRepository $emprunteurRepository): Response
    {
        return $this->render('emprunteur/index.html.twig', [
            'emprunteurs' => $emprunteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_emprunteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emprunteur = new Emprunteur();
        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emprunteur);
            $entityManager->flush();

            return $this->redirectToRoute('app_emprunteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emprunteur/new.html.twig', [
            'emprunteur' => $emprunteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunteur_show', methods: ['GET'])]
    public function show(Emprunteur $emprunteur): Response
    {
        return $this->render('emprunteur/show.html.twig', [
            'emprunteur' => $emprunteur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emprunteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emprunteur $emprunteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmprunteurType::class, $emprunteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_emprunteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('emprunteur/edit.html.twig', [
            'emprunteur' => $emprunteur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunteur_delete', methods: ['POST'])]
    public function delete(Request $request, Emprunteur $emprunteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emprunteur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($emprunteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_emprunteur_index', [], Response::HTTP_SEE_OTHER);
    }
}
