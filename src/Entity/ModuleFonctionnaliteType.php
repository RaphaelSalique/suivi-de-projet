<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\DBAL\Types\StatutType;

/**
 * types de module / fonctionnalité.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ModuleRepository")
 */
class ModuleFonctionnaliteType
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
     * @ORM\Column(type="string", length=80)
     */
    protected $libelle;

    /**
     * @var Entree[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Entree", mappedBy="module", cascade={"persist"})
     */
    protected $entrees;

    /**
     * @var ModuleFonctionnaliteType[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ModuleFonctionnaliteType", mappedBy="parent")
     **/
    private $children;

    /**
     * @var ModuleFonctionnaliteType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ModuleFonctionnaliteType", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;

    /**
     * ModuleFonctionnaliteType constructor.
     */
    public function __construct()
    {
        $this->entrees = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return ModuleFonctionnaliteType
     */
    public function setLibelle(string $libelle): ModuleFonctionnaliteType
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Entree[]|ArrayCollection
     */
    public function getEntrees()
    {
        return $this->entrees;
    }

    /**
     * @param Entree[]|ArrayCollection $entrees
     * @return ModuleFonctionnaliteType
     */
    public function setEntrees($entrees)
    {
        $this->entrees = $entrees;
        return $this;
    }

    /**
     * @return ModuleFonctionnaliteType[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ModuleFonctionnaliteType[]|ArrayCollection $children
     * @return ModuleFonctionnaliteType
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return ModuleFonctionnaliteType
     */
    public function getParent(): ModuleFonctionnaliteType
    {
        return $this->parent;
    }

    /**
     * @param ModuleFonctionnaliteType $parent
     * @return ModuleFonctionnaliteType
     */
    public function setParent(ModuleFonctionnaliteType $parent): ModuleFonctionnaliteType
    {
        $this->parent = $parent;
        return $this;
    }

    //#endregion setters et getters
    //#region fonctions métier
    /**
     * @return string
     */
    public function getBreadcrumb(): string
    {
        $affichage = '';
        if ($this->parent) {
            $affichage .= $this->parent->getBreadcrumb().' > ';
        }
        $affichage .= $this->libelle;

        return $affichage;
    }

    /**
     * @return int
     */
    public function getCompteEntreeOuvertTotal(): int
    {
        $total = 0;
        foreach ($this->entrees as $entree) {
            if ($entree->getStatut() == StatutType::OUVERT) {
                $total++;
            }
        }
        foreach ($this->children as $child) {
            $total += $child->getCompteEntreeOuvertTotal();
        }

        return $total;
    }

    /**
     * @return int
     */
    public function getCompteEntreeFermeTotal(): int
    {
        $total = 0;
        foreach ($this->entrees as $entree) {
            if ($entree->getStatut() != StatutType::OUVERT) {
                $total++;
            }
        }
        foreach ($this->children as $child) {
            $total += $child->getCompteEntreeFermeTotal();
        }

        return $total;
    }

    //#endregion fonctions métier
}
