<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique="true")
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\File(mimeTypes={"image/png", "image/jpg", "image/gif", "image/jpeg",})
     */
    private $previewPicture;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="Category", mappedBy="product", cascade={"persist"})
     */
    private $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->uuid = UuidV4::v4();
    }

    public function getUuid(): ?UuidV4
    {
        return $this->uuid;
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

    public function getPreviewPicture(): ?string
    {
        return $this->previewPicture;
    }

    public function setPreviewPicture(?string $previewPicture): self
    {
        $this->previewPicture = $previewPicture;

        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): void
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
            $category->addProduct($this);
        }
    }

    public function removeCategory(Category $category){
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
            if ($category->addProduct($this) === $this) {
                $category->addProduct(null);
            }
        }
    }
}
