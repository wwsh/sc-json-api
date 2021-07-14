<?php

namespace App\Entity;

use App\Repository\SongDataRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SongDataRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="checksum_idx", fields={"checksum"}),
 *     @ORM\Index(name="updated_idx", fields={"updated"})
 *     })
 * })
 */
class SongData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $artist;

    /**
     * @ORM\Column(type="integer", nullable=true, nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $catno;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rawSongtitle;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $checksum;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mesh;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var SongComment[]
     * @ORM\OneToMany(targetEntity="App\Entity\SongComment", mappedBy="song")
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    private $country;

    public function __construct()
    {
        $this->created = $this->updated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCatno(): ?string
    {
        return $this->catno;
    }

    public function setCatno(string $catno): self
    {
        $this->catno = $catno;

        return $this;
    }

    public function getRawSongtitle(): ?string
    {
        return $this->rawSongtitle;
    }

    public function setRawSongtitle(string $rawSongtitle): self
    {
        $this->rawSongtitle = $rawSongtitle;

        return $this;
    }

    public function getChecksum(): ?string
    {
        return $this->checksum;
    }

    public function setChecksum(string $checksum): self
    {
        $this->checksum = $checksum;

        return $this;
    }

    public function getMesh(): ?string
    {
        return $this->mesh;
    }

    public function setMesh(string $mesh): self
    {
        $this->mesh = $mesh;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return SongComment[]
     */
    public function getComments(): ?Collection
    {
        return $this->comments;
    }

    /**
     * @param SongComment[] $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }
}
