<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandeDetailRepository;

#[ORM\Entity(repositoryClass: CommandeDetailRepository::class)]
#[ApiResource]
class CommandeDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $reduction = null;

    #[ORM\ManyToOne(inversedBy: 'commandeDetails', targetEntity: Commande::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?commande $com = null;

    #[ORM\ManyToOne(inversedBy: 'commandeDetails', targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?produit $pro = null;

    #[ORM\OneToMany(mappedBy: 'prduit', targetEntity: Facture::class)]
    private Collection $factures;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getReduction(): ?string
    {
        return $this->reduction;
    }

    public function setReduction(string $reduction): static
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getCom(): ?commande
    {
        return $this->com;
    }

    public function setCom(?commande $com): static
    {
        $this->com = $com;

        return $this;
    }

    public function getPro(): ?produit
    {
        return $this->pro;
    }

    public function setPro(?produit $pro): static
    {
        $this->pro = $pro;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setPrduit($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getPrduit() === $this) {
                $facture->setPrduit(null);
            }
        }

        return $this;
    }
}
