<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sauvegardes des préférences de recherche (filtrage).
 *
 * @ORM\Entity
 */
class Recherche
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
     * @ORM\Column(type="text")
     */
    protected $parametre;

    /**
     * @var User
     *
     *  @ORM\ManyToOne(targetEntity="App\Entity\User")
     *  @ORM\JoinColumn(nullable=false)
     */
    protected $user;

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
     * @param string $parametre
     *
     * @return Recherche
     */
    public function setParametre($parametre): Recherche
    {
        $this->parametre = $parametre;

        return $this;
    }

    /**
     * @return string
     */
    public function getParametre(): string
    {
        return $this->parametre;
    }

    /**
     * @param User $user
     *
     * @return Recherche
     */
    public function setUser(User $user): Recherche
    {
        $this->user = $user;

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
     * affichage des paramètres.
     *
     * @return string
     */
    public function afficher(): string
    {
        // true permet de convertir la chaîne Json en tableau associatif
        $parametres = explode('&', $this->getParametre());
        $affichageTab = array();
        foreach ($parametres as $parametreStr) {
            $parametreTab = explode('=', $parametreStr);
            if ((isset($parametreTab[1])) && (trim($parametreTab[1]) != '')) {
                $affichageTab[] = $parametreTab[0].' vaut '.str_replace('+', ' ', $parametreTab[1]);
            }
        }

        return implode(', ', $affichageTab);
    }
}
