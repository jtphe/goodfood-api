<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $id;

    #[ORM\Column(type: 'string', length: 55)]
    /**
     * @Groups("read")
     */
    private $name;

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    /**
     * @Groups("read")
     */
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    /**
     * @Groups("read")
     */
    private $image;

    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $stock;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'products')]
    /**
     * @Groups("read")
     */
    private $restaurant;

    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $productType;

    #[ORM\Column(type: 'float')]
    /**
     * @Groups("read")
     */
    private $price;

    #[ORM\Column(type: 'integer', nullable: true)]
    /**
     * @Groups("read")
     */
    private $discount;

    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'products')]
    private Collection $orders;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'products')]
    private Collection $menus;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getProductType(): ?int
    {
        return $this->productType;
    }

    public function setProductType(int $productType): self
    {
        $this->productType = $productType;

        return $this;
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

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @ReturnTypeWillChange
     * @return mixed
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @psalm-pure
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'image'=> $this->image,
            'discount'=> $this->discount,
            'restaurant'=> $this->restaurant  ? $this->restaurant->getId() : null,
            'productType'=> $this->productType,
            'stock' => $this->stock,
            'price' => $this->price
        );
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
            $order->addProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addProduct($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeProduct($this);
        }

        return $this;
    }
}
