<?php 

namespace App\Service;

use App\Entity\Livre;
use Exception;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    
    private $client;
    private $apiBaseUrl;
    private $token;

    public function __construct(HttpClientInterface $client, string $apiBaseUrl, string $apiToken)
    {
        $this->client = $client;
        $this->apiBaseUrl = $apiBaseUrl;
        $this->token = $apiToken;
    }
    

    public function getAllProjects(): array
    {
        $response = $this->client->request(
            'GET', $this->apiBaseUrl . '/projets'
        );

        if ($response->getStatusCode() !== 200) {
            throw new Exception("Erreur de recuperation des projets", 1);
        }

        return $response->toArray();
    }

    
    // $projectData = [
    //     'name' => $livre->getTitre(),//
    //     'startDate' => $livre->getDatePublication()->format('Y-m-d'),
    //     'deliveryDate' => $livre->getDatePublication()->format('Y-m-d'),
    //     'createdBy' => "CRM DMM",
    //     'token' => $token,
    //     'projectypeId' => [
    //         'id' => $livre->getCategorie()->getId(),
    //         // 'nom' => $livre->getCategorie()->getNom(),
    //     ]
    // ];

    public function createProject(Livre $livre) 
    {        
        $projectData = [
            'name' => $livre->getTitre(),//
            'startDate' => $livre->getDatePublication()->format('Y-m-d'),
            'deliveryDate' => $livre->getDatePublication()->format('Y-m-d'),
            'createdBy' => "CRM DMM",
            'token' => $this->token,
            'projectypeId' => null,
            "domaine" => [
                "nomDomaine" => "Test.com"
            ],
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
            // Récupérer le contenu de la réponse de l'exception
            $errorContent = $e->getResponse()->getContent(false); 
            $errorData = json_decode($errorContent, true);
            $errorMessage = isset($errorData['error']) ? $errorData['error'] : "Erreur inconnue.";
          //  return $errorMessage;
            throw new Exception($errorMessage, $e->getCode());
        }
    }



}
