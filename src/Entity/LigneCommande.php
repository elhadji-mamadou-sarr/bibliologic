<?php

namespace App\Entity;

use App\Repository\LigneCommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneCommandeRepository::class)]
class LigneCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $premierPaiement = null;

    #[ORM\Column(length: 255)]
    private ?string $domaine = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $rubriques = [];

    #[ORM\Column(length: 255)]
    private ?string $informationUtil = null;

    #[ORM\ManyToOne(inversedBy: 'ligneCommande')]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPremierPaiement(): ?\DateTimeInterface
    {
        return $this->premierPaiement;
    }

    public function setPremierPaiement(\DateTimeInterface $premierPaiement): static
    {
        $this->premierPaiement = $premierPaiement;

        return $this;
    }

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getRubriques(): array
    {
        return $this->rubriques;
    }

    public function setRubriques(array $rubriques): static
    {
        $this->rubriques = $rubriques;

        return $this;
    }

    public function getInformationUtil(): ?string
    {
        return $this->informationUtil;
    }

    public function setInformationUtil(string $informationUtil): static
    {
        $this->informationUtil = $informationUtil;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
