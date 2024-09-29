<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livre')]
class LivreController extends AbstractController
{
    private $apiService;
    function __construct(CallApiService $apiService) {
        $this->apiService = $apiService;
    }

    #[Route('/', name: 'app_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository, CallApiService $apiService): Response
    {
        $livres = $livreRepository->findAll();
        $projets = $apiService->getAllProjects();

        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
            'projets' => $projets,
        ]);
    }

    #[Route('/livre/search', name: 'app_livre_search', methods: ['GET'])]
    public function searchLivre(Request $request, LivreRepository $livreRepository): JsonResponse
    {
        $searchTerm = $request->query->get('search', '');

        // Appeler la méthode de recherche du repository
        $livres = $livreRepository->findByTitle($searchTerm);

        // Retourner une réponse JSON avec les résultats trouvés
        return $this->json([
            'livres' => $livres,
        ], 200, []);
    }

    #[Route('/new', name: 'app_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $token = $request->request->get('token');

            if ($livre->isProjet()) {
                try {
                    $response = $this->apiService->createProject($livre, $token);  

                    $this->addFlash('success', 'Projet créé avec succès');

                } catch (Exception $e) {
                    // Gestion des erreurs
                    $this->addFlash('error', $e->getMessage());
                    return $this->redirectToRoute('app_livre_new');
                }
            }
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $token = $request->request->get('token');

            if ($livre->isProjet()) {
                try {
                    $this->apiService->createProject($livre, $token);  
                    $this->addFlash('success', 'Projet créé avec succès');

                } catch (Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                    return $this->redirectToRoute('app_livre_edit', ['id' => $livre->getId()]);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
    }


   


}
