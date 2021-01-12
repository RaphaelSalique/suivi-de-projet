<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\DBAL\Types\SeveriteType;
use App\DBAL\Types\StatutType;
use App\DBAL\Types\TypeEntreeType;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

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
     * @var string|null
     *
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    protected $reference;

    /**
     * @var string|null
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
     * @var boolean|null
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $testable;

    /**
     * @var string|null
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

    /**
     * @var User
     *
     *  @ORM\ManyToOne(targetEntity="App\Entity\User")
     *  @ORM\JoinColumn(nullable=false)
     */
    protected $assigne;

    /**
     * @var User
     *
     *  @ORM\ManyToOne(targetEntity="App\Entity\User")
     *  @ORM\JoinColumn(nullable=false)
     */
    protected $createur;

    /**
     * @var PieceJointe[]|ArrayCollection
     *
     *  @ORM\OneToMany(targetEntity="App\Entity\PieceJointe", mappedBy="entree")
     */
    protected $images;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $dateheure;

    /**
     * @var  \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $maj;

    /**
     * Entree constructor.
     */
    public function __construct()
    {
        $this->dateheure = new \DateTime();
        $this->maj = new \DateTime();
        $this->commentaires = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    //#region setters et getters
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string|null $reference
     * @return Entree
     */
    public function setReference(?string $reference): Entree
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string|null $titre
     * @return Entree
     */
    public function setTitre(?string $titre): Entree
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * @return ModuleFonctionnaliteType|null
     */
    public function getModule(): ?ModuleFonctionnaliteType
    {
        return $this->module;
    }

    /**
     * @param ModuleFonctionnaliteType $module
     * @return Entree
     */
    public function setModule(ModuleFonctionnaliteType $module): Entree
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Entree
     */
    public function setDescription(string $description): Entree
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Entree
     */
    public function setType(string $type): Entree
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeverite(): string
    {
        return $this->severite;
    }

    /**
     * @param string $severite
     * @return Entree
     */
    public function setSeverite(string $severite): Entree
    {
        $this->severite = $severite;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatut(): string
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     * @return Entree
     */
    public function setStatut(string $statut): Entree
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return boolean|null
     */
    public function isTestable(): ?bool
    {
        return $this->testable;
    }

    /**
     * @param bool|null $testable
     * @return Entree
     */
    public function setTestable(?bool $testable): Entree
    {
        $this->testable = $testable;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDuree(): ?string
    {
        return $this->duree;
    }

    /**
     * @param string|null $duree
     * @return Entree
     */
    public function setDuree(?string $duree): Entree
    {
        $this->duree = $duree;
        return $this;
    }

    /**
     * @return Commentaire
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * @param Commentaire $commentaires
     * @return Entree
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
        return $this;
    }

    /**
     * @param Commentaire $commentaire
     * @return self
     */
    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setEntree($this);
        }

        return $this;
    }

    /**
     * @param Commentaire $commentaire
     * @return self
     */
    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAssigne(): ?User
    {
        return $this->assigne;
    }

    /**
     * @param User $assigne
     * @return Entree
     */
    public function setAssigne(User $assigne): Entree
    {
        $this->assigne = $assigne;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreateur(): ?User
    {
        return $this->createur;
    }

    /**
     * @param User $createur
     * @return Entree
     */
    public function setCreateur(User $createur): Entree
    {
        $this->createur = $createur;
        return $this;
    }

    /**
     * @return PieceJointe[]|ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param PieceJointe[]|ArrayCollection $images
     * @return Entree
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @param PieceJointe $image
     * @return self
     */
    public function addImage(PieceJointe $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setEntree($this);
        }

        return $this;
    }

    /**
     * @param PieceJointe $image
     * @return self
     */
    public function removeImage(PieceJointe $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

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
     * @return Entree
     */
    public function setDateheure(\DateTime $dateheure): Entree
    {
        $this->dateheure = $dateheure;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getMaj(): \DateTime
    {
        return $this->maj;
    }

    /**
     * @param \DateTime $maj
     * @return Entree
     */
    public function setMaj(\DateTime $maj): Entree
    {
        $this->maj = $maj;
        return $this;
    }

    //#endregion setters et getters
    //#region méthodes métier

    /**
     * affiche Testable.
     *
     * @return string
     */
    public function afficheTestable(): string
    {
        return ($this->testable) ? 'X' : '-';
    }

    /**
     * Affiche duree.
     *
     * @return string
     */
    public function afficheDuree(): string
    {
        return ((float) $this->duree > 0) ? (string) $this->duree : '-';
    }

    /**
     * flux Atom : le titre.
     *
     * @return string|null
     */
    public function getFeedItemTitle(): ?string
    {
        return $this->titre;
    }

    /**
     * flux Atom: le contenu du ticket (commentaires et images incluses).
     *
     * @return string
     */
    public function getFeedItemDescription(): string
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
    public function getFeedItemPubDate(): \DateTime
    {
        return $this->maj;
    }

    /**
     * @todo à corriger
     * flux Atom : le nom de la route nécessaire pour visualiser l'entrée.
     *
     * @return string
     */
    public function getFeedItemRouteName(): string
    {
        return 'rs_suivideprojet_modulefonctionnalitetype_afficherdetailmodulestoususers';
    }

    /**
     * flux Atom : les paramètres nécessaires pour la route.
     *
     * @return array
     */
    public function getFeedItemRouteParameters(): array
    {
        return ['module' => $this->module->getId()];
    }

    /**
     * flux Atom : le lien de visualisation.
     *
     * @return string
     */
    public function getFeedItemUrlAnchor(): string
    {
        return '';
    }
    //#endregion méthodes métier
}
