<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SupplyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplyRepository::class)]
#[ApiResource]
class Supply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantity;

    #[ORM\Column(type: 'float', nullable: true)]
    private $price;

    #[ORM\ManyToOne(targetEntity: restaurant::class, inversedBy: 'supplies')]
    #[ORM\JoinColumn(nullable: false)]
    private $restaurant;

    #[ORM\ManyToOne(targetEntity: supplier::class, inversedBy: 'supplies')]
    #[ORM\JoinColumn(nullable: false)]
    private $supplier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRestaurant(): ?restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getSupplier(): ?supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'name'=> $this->name,
            'quantity'=> $this->quantity,
            'price'=> $this->price,
            'restaurant'=> $this->restaurant,
            'supplier'=> $this->supplier,
        );
    }
}
