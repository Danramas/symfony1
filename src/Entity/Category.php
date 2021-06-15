<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     *
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Product" ,cascade={"persist"})
     * @ORM\JoinTable(name="category_products",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_uuid", referencedColumnName="uuid")}
     *      )
     */
    private $product;

    public function __construct()
    {
        $this->product = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->addCategory($this);
        }

    }
    public function __toString()
    {
        return $this->name;
    }

    public function removeProduct(Product $product)
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
            if ($product->getCategory() === $this) {
                $product->addCategory(null);
            }
        }
    }

}
