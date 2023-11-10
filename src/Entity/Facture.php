<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column]
    // private ?int $fact_id = null;

    #[ORM\ManyToOne(inversedBy: 'factures', targetEntity: Utilisateur::class, cascade: ['persist'])]
    private ?Utilisateur $client = null;

    #[ORM\ManyToOne(inversedBy: 'factures',targetEntity: Adresse::class, cascade: ['persist'])]
    private ?Adresse $adresse_livraison = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Adresse $code_postal = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Adresse $ville = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Utilisateur $email = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Adresse $telephone = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?CommandeDetail $prduit = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?CommandeDetail $prix = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?CommandeDetail $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Commande $adresse_facturation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getFactId(): ?int
    // {
    //     return $this->fact_id;
    // }

    // public function setFactId(int $fact_id): static
    // {
    //     $this->fact_id = $fact_id;

    //     return $this;
    // }

    public function getClient(): ?Utilisateur
    {
        return $this->client;
    }

    public function setClient(?Utilisateur $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getAdresseLivraison(): ?Adresse
    {
        return $this->adresse_livraison;
    }

    public function setAdresseLivraison(?Adresse $adresse_livraison): static
    {
        $this->adresse_livraison = $adresse_livraison;

        return $this;
    }

    public function getCodePostal(): ?Adresse
    {
        return $this->code_postal;
    }

    public function setCodePostal(?Adresse $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?Adresse
    {
        return $this->ville;
    }

    public function setVille(?Adresse $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getEmail(): ?Utilisateur
    {
        return $this->email;
    }

    public function setEmail(?Utilisateur $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?Adresse
    {
        return $this->telephone;
    }

    public function setTelephone(?Adresse $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPrduit(): ?CommandeDetail
    {
        return $this->prduit;
    }

    public function setPrduit(?CommandeDetail $prduit): static
    {
        $this->prduit = $prduit;

        return $this;
    }

    public function getPrix(): ?CommandeDetail
    {
        return $this->prix;
    }

    public function setPrix(?CommandeDetail $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?CommandeDetail
    {
        return $this->quantite;
    }

    public function setQuantite(?CommandeDetail $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getAdresseFacturation(): ?Commande
    {
        return $this->adresse_facturation;
    }

    public function setAdresseFacturation(?Commande $adresse_facturation): static
    {
        $this->adresse_facturation = $adresse_facturation;

        return $this;
    }
}
