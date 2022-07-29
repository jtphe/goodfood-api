<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: product::class, inversedBy: 'menus')]
    private Collection $products;

    #[ORM\ManyToMany(targetEntity: Order::class, inversedBy: 'menus')]
    private Collection $orders;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        $this->orders->removeElement($order);

        return $this;
    }
}
