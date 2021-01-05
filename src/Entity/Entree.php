<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\DBAL\Types\SeveriteType;
use App\DBAL\Types\StatutType;
use App\DBAL\Types\TypeEntreeType;
//use Fresh\Bundle\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
//use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

/**
 * les entrées.
 *
 * @ORM\Entity(repositoryClass="App\Repository\EntreeRepository")
 */
class Entree// implements RoutedItemInterface
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
     * @var string
     *
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $reference;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $titre;

    /**
     * @var ModuleFonctionnaliteType
     *
     *  @ORM\ManyToOne(targetEntity="App\Entity\ModuleFonctionnaliteType", inversedBy="entrees")
     *  @ORM\JoinColumn(nullable=false)
     */
    protected $module;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var string
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\TypeEntreeType")
     * @ORM\Column(type="TypeEntreeType", nullable=false)
     */
    protected $type;

    /**
     * @var string
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\SeveriteType")
     * @ORM\Column(type="SeveriteType", nullable=false)
     */
    protected $severite;

    /**
     * @var string
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\StatutType")
     * @ORM\Column(type="StatutType", nullable=false)
     */
    protected $statut;

    /**
     * @var string
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $testable;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $duree;

    /**
     * @var Commentaire
     *
     *  @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="entree", cascade={"persist"})
     *  @ORM\JoinColumn(nullable=false)
     */
    protected $commentaires;

//    /**
//     * @var Entree
//     *
//     *  @ORM\ManyToOne(targetEntity="RS\UserBundle\Entity\User")
//     *  @ORM\JoinColumn(nullable=false)
//     */
//    protected $assigne;
//
//    /**
//     * @var Entree
//     *
//     *  @ORM\ManyToOne(targetEntity="RS\UserBundle\Entity\User")
//     *  @ORM\JoinColumn(nullable=false)
//     */
//    protected $createur;

    /**
     * @var Entree
     *
     *  @ORM\OneToMany(targetEntity="App\Entity\PieceJointe", mappedBy="entree")
     */
    protected $images;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     */
    protected $dateheure;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime")
     */
    protected $maj;

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
     * Set reference.
     *
     * @param string $reference
     *
     * @return Entree
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference.
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set titre.
     *
     * @param string $titre
     *
     * @return Entree
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre.
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Entree
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type.
     *
     * @param \App\DBAL\TypeEntreeType $type
     *
     * @return Entree
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \TypeEntreeType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set severite.
     *
     * @param \SeveriteType $severite
     *
     * @return Entree
     */
    public function setSeverite($severite)
    {
        $this->severite = $severite;

        return $this;
    }

    /**
     * Get severite.
     *
     * @return \SeveriteType
     */
    public function getSeverite()
    {
        return $this->severite;
    }

    /**
     * Set statut.
     *
     * @param \StatutType $statut
     *
     * @return Entree
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut.
     *
     * @return \StatutType
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set testable.
     *
     * @param bool $testable
     *
     * @return Entree
     */
    public function setTestable($testable)
    {
        $this->testable = $testable;

        return $this;
    }

    /**
     * Get testable.
     *
     * @return bool
     */
    public function isTestable()
    {
        return $this->testable;
    }

    /**
     * affiche Testable.
     *
     * @return string
     */
    public function afficheTestable()
    {
        return ($this->testable) ? 'X' : '-';
    }

    /**
     * Set duree.
     *
     * @param string $duree
     *
     * @return Entree
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree.
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Affiche duree.
     *
     * @return string
     */
    public function afficheDuree()
    {
        return ($this->duree > 0) ? $this->duree : '-';
    }

    /**
     * Set module.
     *
     * @param \App\Entity\ModuleFonctionnaliteType $module
     *
     * @return Entree
     */
    public function setModule(\App\Entity\ModuleFonctionnaliteType $module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module.
     *
     * @return \App\Entity\ModuleFonctionnaliteType
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Add commentaire.
     *
     * @param \App\Entity\Commentaire $commentaire
     *
     * @return Entree
     */
    public function addCommentaire(\App\Entity\Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;
        $commentaire->setEntree($this);

        return $this;
    }

    /**
     * Remove commentaire.
     *
     * @param \App\Entity\Commentaire $commentaire
     *
     * @return Entree
     */
    public function removeCommentaire(\App\Entity\Commentaire $commentaire)
    {
        $this->commentaires->removeElement($commentaire);

        return $this;
    }

    /**
     * Get commentaires.
     *
     * @return \App\Entity\Commentaire
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Add image.
     *
     * @param \App\Entity\PieceJointe $image
     *
     * @return Entree
     */
    public function addImage(\App\Entity\PieceJointe $image)
    {
        $this->images[] = $image;
        $image->setEntree($this);

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \App\Entity\PieceJointe $image
     *
     * @return Entree
     */
    public function removeImage(\App\Entity\PieceJointe $image)
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * Get images.
     *
     * @return \App\Entity\PieceJointe
     */
    public function getImages()
    {
        return $this->images;
    }
//
//    /**
//     * Set assigne.
//     *
//     * @param \RS\UserBundle\Entity\User $assigne
//     *
//     * @return Commentaire
//     */
//    public function setAssigne(\RS\UserBundle\Entity\User $assigne)
//    {
//        $this->assigne = $assigne;
//
//        return $this;
//    }
//
//    /**
//     * Get assigne.
//     *
//     * @return \App\Entity\User
//     */
//    public function getAssigne()
//    {
//        return $this->assigne;
//    }
//
//    /**
//     * Set createur.
//     *
//     * @param \RS\UserBundle\Entity\User $createur
//     *
//     * @return Commentaire
//     */
//    public function setCreateur(\RS\UserBundle\Entity\User $createur)
//    {
//        $this->createur = $createur;
//
//        return $this;
//    }
//
//    /**
//     * Get createur.
//     *
//     * @return \App\Entity\User
//     */
//    public function getCreateur()
//    {
//        return $this->createur;
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

    /**
     * Set maj.
     *
     * @param string $maj
     *
     * @return Commentaire
     */
    public function setMaj(\DateTime $maj)
    {
        $this->maj = $maj;

        return $this;
    }

    /**
     * Get maj.
     *
     * @return string
     */
    public function getMaj()
    {
        return $this->maj;
    }

    /**
     * flux Atom : le titre.
     *
     * @return string
     */
    public function getFeedItemTitle()
    {
        return $this->titre;
    }

    /**
     * flux Atom: le contenu du ticket (commentaires et images incluses).
     *
     * @return string
     */
    public function getFeedItemDescription()
    {
        $contenu = '<p>'.nl2br($this->description).'</p>';
        if ($this->commentaires->count() > 0) {
            $contenu .= '<ul>';
            foreach ($this->commentaires as $commentaire) {
                $contenu .= '<li>
                <p>Commentaire par '.$commentaire->getUser()->getPrenom().' '.$commentaire->getUser()->getNom().' le '.$commentaire->getDateheure()->format('d/m/Y à H:i:s').'</p>
                <p>'.nl2br($commentaire->getCommentaire()).'</p></li>';
            }
            $contenu .= '</ul>';
        }

        return $contenu;
    }

    /**
     * flux Atom : date de mise à jour.
     *
     * @return \DateTime
     */
    public function getFeedItemPubDate()
    {
        return $this->maj;
    }

    /**
     * flux Atom : le nom de la route nécessaire pour visualiser l'entrée.
     *
     * @return string
     */
    public function getFeedItemRouteName()
    {
        return 'rs_suivideprojet_modulefonctionnalitetype_afficherdetailmodulestoususers';
    }

    /**
     * flux Atom : les paramètres nécessaires pour la route.
     *
     * @return array
     */
    public function getFeedItemRouteParameters()
    {
        return array('module' => $this->module->getId());
    }

    /**
     * flux Atom : le lien de visualisation.
     *
     * @return string
     */
    public function getFeedItemUrlAnchor()
    {
        return '';
    }

    public function __construct()
    {
        $this->dateheure = new \DateTime();
        $this->maj = new \DateTime();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
