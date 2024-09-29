<?php


namespace App\Service;

use App\Entity\LigneCommande;
use Exception;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CreateProjectApiService
{

    private $client;
    private $apiBaseUrl;
    private $token;

    public function __construct(HttpClientInterface $client, string $apiBaseUrl, string $apiToken) {
        $this->client = $client;
        $this->apiBaseUrl = $apiBaseUrl;
        $this->token = $apiToken;
    }
    

    public function createProject(LigneCommande $ligne) 
    {        
        $rubriques = $ligne->getRubriques();
        
        // Transforme le tableau de chaînes en tableau d'objets
        $rubriquesFormatted = array_map(function($titre) {
            return [
                'titre' => $titre,
                'parent' => null,
            ];
        }, $rubriques);
        
        $projectData = [
            'token' => $this->token,
            'name' => $ligne->getDesignation(),
            'startDate' => $ligne->getPremierPaiement()->format('Y-m-d\TH:i:sP'), // Format ISO 8601
            'deliveryDate' => $ligne->getPremierPaiement()->format('Y-m-d\TH:i:sP'), // Format ISO 8601
            'createdBy' => "CRM DMM",
            "description" => "description du projet",
            'typeProjectId' => 1, // Remplacez par la valeur correcte si nécessaire
            "domaine" => [
                "nomDomaine" => $ligne->getDomaine(),
            ],
            "userId" => 24,
            "rubriques" => $rubriquesFormatted, 
        ];
        
        try {
            $response = $this->client->request(
                'POST',
                $this->apiBaseUrl . '/projets/create', 
                [
                    'json' => $projectData
                ]
            );
            return $response->toArray(); 
        } catch (ClientException $e) {
            $errorContent = $e->getResponse()->getContent(false);
            $errorData = json_decode($errorContent, true);
            $errorMessage = isset($errorData['error']) ? $errorData['error'] : "Erreur inconnue.";

            throw new Exception($errorMessage, $e->getCode());
        }
    }

}