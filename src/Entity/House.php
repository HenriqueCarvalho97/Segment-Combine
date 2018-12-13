<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HouseRepository")
 */
class House
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="id")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $remodelationYear;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="datetime")
     */
    private $constructionYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $rooms;

    /**
     * @ORM\Column(type="float")
     */
    private $netArea;

    /**
     * @ORM\Column(type="float")
     */
    private $grossArea;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HouseImage", mappedBy="house", orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainImage;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRemodelationYear(): ?\DateTimeInterface
    {
        return $this->remodelationYear;
    }

    public function setRemodelationYear(\DateTimeInterface $remodelationYear): self
    {
        $this->remodelationYear = $remodelationYear;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getConstructionYear(): ?\DateTimeInterface
    {
        return $this->constructionYear;
    }

    public function setConstructionYear(\DateTimeInterface $constructionYear): self
    {
        $this->constructionYear = $constructionYear;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getNetArea(): ?float
    {
        return $this->netArea;
    }

    public function setNetArea(float $netArea): self
    {
        $this->netArea = $netArea;

        return $this;
    }

    public function getGrossArea(): ?float
    {
        return $this->grossArea;
    }

    public function setGrossArea(float $grossArea): self
    {
        $this->grossArea = $grossArea;

        return $this;
    }

    public function getRent(): ?bool
    {
        return $this->rent;
    }

    public function setRent(bool $rent): self
    {
        $this->rent = $rent;

        return $this;
    }

    /**
     * @return Collection|HouseImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(HouseImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setHouse($this);
        }

        return $this;
    }

    public function removeImage(HouseImage $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getHouse() === $this) {
                $image->setHouse(null);
            }
        }

        return $this;
    }


    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(string $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

}
