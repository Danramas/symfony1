<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $previewPicture;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $enabled = 1;


    public function __construct()
    {
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
}
