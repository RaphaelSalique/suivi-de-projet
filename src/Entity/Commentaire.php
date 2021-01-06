<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * les commentaires.
 *
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Entree
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Entree", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $entree;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $commentaire;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $dateheure;

    /**
     * Commentaire constructor.
     */
    public function __construct()
    {
        $this->dateheure = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Entree
     */
    public function getEntree(): Entree
    {
        return $this->entree;
    }

    /**
     * @param Entree $entree
     * @return Commentaire
     */
    public function setEntree(Entree $entree): Commentaire
    {
        $this->entree = $entree;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     * @return Commentaire
     */
    public function setCommentaire(string $commentaire): Commentaire
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Commentaire
     */
    public function setUser(User $user): Commentaire
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateheure(): \DateTime
    {
        return $this->dateheure;
    }

    /**
     * @param \DateTime $dateheure
     * @return Commentaire
     */
    public function setDateheure(\DateTime $dateheure): Commentaire
    {
        $this->dateheure = $dateheure;
        return $this;
    }
}
