<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'boolean')]
    private $archive;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $address;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    private $postalCode;

    #[ORM\Column(type: 'string', length: 55, nullable: true)]
    private $city;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $payment;

    #[ORM\Column(type: 'integer')]
    private $type;

    #[ORM\Column(type: 'integer')]
    private $statut;

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: restaurant::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $restaurant;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'orders')]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'orderMenu', targetEntity: Menu::class)]
    private Collection $menus;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(?string $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

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

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'price'=> $this->price,
            'address'=> $this->address,
            'city'=> $this->city,
            'postalCode'=> $this->postalCode,
            'archive'=> $this->archive,
            'statut'=> $this->statut,
            'type'=> $this->type,
            'user'=> $this->user,
            'restaurant'=> $this->restaurant->getId()
        );
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    // public function addMenu(Menu $menu): self
    // {
    //     if (!$this->menus->contains($menu)) {
    //         $this->menus[] = $menu;
    //         $menu->setOrderMenu($this);
    //     }

    //     return $this;
    // }

    // public function removeMenu(Menu $menu): self
    // {
    //     if ($this->menus->removeElement($menu)) {
    //         // set the owning side to null (unless already changed)
    //         if ($menu->getOrderMenu() === $this) {
    //             $menu->setOrderMenu(null);
    //         }
    //     }

    //     return $this;
    // }


}
