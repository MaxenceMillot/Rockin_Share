<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez entre un nom")
     * @Assert\Length(min="2", max="150",
     *      minMessage="Minimum 2 caractères",
     *      maxMessage="Maximum 150 caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\Length(min="2", max="800",
     *      minMessage="Minimum 2 caractères",
     *      maxMessage="Maximum 800 caractères"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="typeMedia")
     * @ORM\JoinColumn(name="genre_id", referencedColumnName="id",  nullable=true)
     */
    private $genres;

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

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genres;
    }

    /**
     * @param mixed $genre
     */
    public function setCategory($genre)
    {
        $this->genres = $genre;
    }
}
