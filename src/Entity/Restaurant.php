<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\Groups;




#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ApiResource]
class Restaurant implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    /**
     * @Groups("read")
     */
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    /**
     * @Groups("read")
     */
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    /**
     * @Groups("read")
     */
    private $description;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    /**
     * @Groups("read")
     */
    private $phone;

    #[ORM\Column(type: 'string', length: 75)]
    /**
     * @Groups("read")
     */
    private $address;

    #[ORM\Column(type: 'string', length: 15)]
    /**
     * @Groups("read")
     */
    private $postalCode;

    #[ORM\Column(type: 'string', length: 55)]
    /**
     * @Groups("read")
     */
    private $city;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    /**
     * @Groups("read")
     */
    private $photo;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Comment::class, orphanRemoval: true)]
    /**
     * @Ignore
     */
    private $comments;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'restaurant')]
    #[ORM\JoinColumn(nullable: true)]
    private $country;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Supplier::class)]
    /**
     * @Ignore
     */
    private $suppliers;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Supply::class)]
    /**
     * @Ignore
     */
    private $supplies;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Order::class)]
    /**
     * @Ignore
     */
    private $orders;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: User::class)]
    /**
     * @Ignore
     */
    private $user;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Product::class, orphanRemoval: true)]
    /**
     * @Ignore
     */
    private $products;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->suppliers = new ArrayCollection();
        $this->supplies = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }


    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setRestaurant($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRestaurant() === $this) {
                $comment->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAvgRating()
    {
        $comments = $this->getComments();
        $arrayRating = [];
        $sum=0;

        foreach($comments as $comment)
        {
            if($comment->getRating()!=null)
            {
                array_push($arrayRating,$comment->getRating());
                $sum+=$comment->getRating();
            }
        }

        $nb = count($arrayRating);
        return $sum/$nb;
    }


    /**
     * @return Collection<int, Supplier>
     */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
    }

    public function addSupplier(Supplier $supplier): self
    {
        if (!$this->suppliers->contains($supplier)) {
            $this->suppliers[] = $supplier;
            $supplier->setRestaurant($this);
        }

        return $this;
    }

    public function removeSupplier(Supplier $supplier): self
    {
        if ($this->suppliers->removeElement($supplier)) {
            // set the owning side to null (unless already changed)
            if ($supplier->getRestaurant() === $this) {
                $supplier->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Supply>
     */
    public function getSupplies(): Collection
    {
        return $this->supplies;
    }

    public function addSupply(Supply $supply): self
    {
        if (!$this->supplies->contains($supply)) {
            $this->supplies[] = $supply;
            $supply->setRestaurant($this);
        }

        return $this;
    }

    public function removeSupply(Supply $supply): self
    {
        if ($this->supplies->removeElement($supply)) {
            // set the owning side to null (unless already changed)
            if ($supply->getRestaurant() === $this) {
                $supply->setRestaurant(null);
            }
        }

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
            $order->setRestaurant($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getRestaurant() === $this) {
                $order->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getuser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setRestaurant($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRestaurant() === $this) {
                $user->setRestaurant(null);
            }
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'name'=> $this->name,
            'description'=> $this->description,
            'phone'=> $this->phone,
            'address'=> $this->address,
            'postalcode'=> $this->postalCode,
            'city'=> $this->city,
            'country' => $this->country  ? $this->country->getId() : null,
            'avgRating' => $this->getAvgRating()  ? $this->getAvgRating()  : null
        );
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
            $product->setRestaurant($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getRestaurant() === $this) {
                $product->setRestaurant(null);
            }
        }

        return $this;
    }

}
