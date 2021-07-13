<?php

namespace App\Entity;

use App\Repository\SongCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SongCommentRepository::class)
 */
class SongComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var SongData
     * @ORM\ManyToOne(targetEntity="App\Entity\SongData", inversedBy="comments")
     * @ORM\JoinColumn(name="song_id", referencedColumnName="id")
     */
    private $song;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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
     * @return SongData
     */
    public function getSong(): SongData
    {
        return $this->song;
    }

    /**
     * @param SongData $song
     */
    public function setSong(SongData $song): void
    {
        $this->song = $song;
    }
}
