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
     *  @ORM\ManyToOne(targetEntity="App\Entity\Entree", inversedBy="commentaires")
     *  @ORM\JoinColumn(nullable=false)
     */
    protected $entree;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $commentaire;

//    /**
//     * @var Entree
//     *
//     *  @ORM\ManyToOne(targetEntity="RS\UserBundle\Entity\User")
//     *  @ORM\JoinColumn(nullable=false)
//     */
//    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     */
    protected $dateheure;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set commentaire.
     *
     * @param string $commentaire
     *
     * @return Commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire.
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set entree.
     *
     * @param \App\Entity\Entree $entree
     *
     * @return Commentaire
     */
    public function setEntree(\App\Entity\Entree $entree)
    {
        $this->entree = $entree;

        return $this;
    }

    /**
     * Get entree.
     *
     * @return \App\Entity\Entree
     */
    public function getEntree()
    {
        return $this->entree;
    }

//    /**
//     * Set user.
//     *
//     * @param \RS\UserBundle\Entity\User $user
//     *
//     * @return Commentaire
//     */
//    public function setUser(\RS\UserBundle\Entity\User $user)
//    {
//        $this->user = $user;
//
//        return $this;
//    }
//
//    /**
//     * Get user.
//     *
//     * @return \App\Entity\User
//     */
//    public function getUser()
//    {
//        return $this->user;
//    }

    /**
     * Set dateheure.
     *
     * @param string $dateheure
     *
     * @return Commentaire
     */
    public function setDateheure(\DateTime $dateheure)
    {
        $this->dateheure = $dateheure;

        return $this;
    }

    /**
     * Get dateheure.
     *
     * @return string
     */
    public function getDateheure()
    {
        return $this->dateheure;
    }

    public function __construct()
    {
        $this->dateheure = new \DateTime();
    }
}
