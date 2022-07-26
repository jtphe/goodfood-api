<?php

namespace App\Entity;

use App\Repository\CountainRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountainRepository::class)]
class Countain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: order::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $orders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrders(): ?order
    {
        return $this->orders;
    }

    public function setOrders(?order $orders): self
    {
        $this->orders = $orders;

        return $this;
    }
}
