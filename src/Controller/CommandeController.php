<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Form\CommandeType;
use App\Form\LigneCommandeType;
use App\Repository\CommandeRepository;
use App\Service\CreateProjectApiService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{

    private $createProjectApiService;

    public function __construct(CreateProjectApiService $createProjectApiService) {
        $this->createProjectApiService = $createProjectApiService; // Stockez le service dans une propriété
    }
    
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_commande_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {

        $id_ligne = $request->request->get("id_ligne");
        $ligneCommande = $id_ligne ? $entityManager->getRepository(LigneCommande::class)->find($id_ligne) : new LigneCommande();
        $form = $this->createForm(LigneCommandeType::class, $ligneCommande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ligneCommande->setCommande($commande);
            $commande->addLigneCommande($ligneCommande);
            $entityManager->persist($ligneCommande);

            try {
                $this->createProjectApiService->createProject($ligneCommande); // Passez l'objet LigneCommande 
            
                $this->addFlash('success', 'Le projet a été créé avec succès.');

            } catch (Exception $e) {
                $this->addFlash('error', 'Erreur lors de la création du projet: ' . $e->getMessage());
            }
                $entityManager->flush();
                return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
            'form' => $form,
            'lignes' => $commande->getLigneCommandes(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
