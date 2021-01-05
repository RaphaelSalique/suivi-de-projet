<?php

namespace App\Entity;

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
     * @var Entree
     *
     *  @ORM\OneToMany(targetEntity="App\Entity\Entree", mappedBy="module", cascade={"persist"})
     *  @ORM\JoinColumn(nullable=false)
     */
    protected $entrees;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ModuleFonctionnaliteType", mappedBy="parent")
     **/
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ModuleFonctionnaliteType", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parent;

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
     * Set libelle.
     *
     * @param string $libelle
     *
     * @return ModuleFonctionnaliteType
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle.
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Add entree.
     *
     * @param \App\Entity\Entree $entree
     *
     * @return ModuleFonctionnaliteType
     */
    public function addEntree(\App\Entity\Entree $entree)
    {
        $this->entrees[] = $entree;
        $entree->setModule($this);

        return $this;
    }

    /**
     * Remove entree.
     *
     * @param \App\Entity\Entree $entree
     *
     * @return ModuleFonctionnaliteType
     */
    public function removeEntree(\App\Entity\Entree $entree)
    {
        $this->entrees->removeElement($entree);

        return $this;
    }

    /**
     * Get entrees.
     *
     * @return \App\Entity\Entree
     */
    public function getEntrees()
    {
        return $this->entrees;
    }

    /**
     * Add child.
     *
     * @param \App\Entity\ModuleFonctionnaliteType $child
     *
     * @return ModuleFonctionnaliteType
     */
    public function addChild(\App\Entity\ModuleFonctionnaliteType $child)
    {
        $this->children[] = $child;
        $child->setParent($this);

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \App\Entity\ModuleFonctionnaliteType $child
     *
     * @return ModuleFonctionnaliteType
     */
    public function removeChild(\App\Entity\ModuleFonctionnaliteType $child)
    {
        $this->children->removeElement($child);

        return $this;
    }

    /**
     * Get children.
     *
     * @return \App\Entity\ModuleFonctionnaliteType
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent.
     *
     * @param \App\Entity\ModuleFonctionnaliteType $parent
     *
     * @return Commentaire
     */
    public function setParent(\App\Entity\ModuleFonctionnaliteType $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \App\Entity\ModuleFonctionnaliteType
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getBreadcrumb()
    {
        $affichage = '';
        if ($this->parent) {
            $affichage .= $this->parent->getBreadcrumb().' > ';
        }
        $affichage .= $this->libelle;

        return $affichage;
    }

    public function getCompteEntreeOuvertTotal()
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

    public function getCompteEntreeFermeTotal()
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

    public function __construct()
    {
        $this->entrees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
