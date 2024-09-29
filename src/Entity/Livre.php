<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, )]
    #[Assert\NotNull(message:"Le titre est obligatoire")]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"L'auteur est obligatoire")]
    private ?string $auteur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"Le genre du livre est obligatoire")]
    private ?string $genre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"La langue est obligatoire")]
    private ?string $langue = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_publication = null;

    #[ORM\Column]
    #[Assert\NotNull(message:"Le nombre de pade est obligatoire")]
    private ?int $nombre_pages = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"La localisation est obligatoire")]
    private ?string $localisation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message:"L'etat est obligatoire")]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    private ?Categorie $categorie = null;

    #[ORM\Column]
    private ?bool $projet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->date_publication;
    }

    public function setDatePublication(\DateTimeInterface $date_publication): static
    {
        $this->date_publication = $date_publication;

        return $this;
    }

    public function getNombrePages(): ?int
    {
        return $this->nombre_pages;
    }

    public function setNombrePages(int $nombre_pages): static
    {
        $this->nombre_pages = $nombre_pages;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function isProjet(): ?bool
    {
        return $this->projet;
    }

    public function setProjet(bool $projet): static
    {
        $this->projet = $projet;

        return $this;
    }
}
