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

//    /**
//     * @var Entree
//     *
//     *  @ORM\ManyToOne(targetEntity="RS\UserBundle\Entity\User")
//     *  @ORM\JoinColumn(nullable=false)
//     */
//    protected $user;

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
     * Set parametre.
     *
     * @param string $parametre
     *
     * @return Recherche
     */
    public function setParametre($parametre)
    {
        $this->parametre = $parametre;

        return $this;
    }

    /**
     * Get parametre.
     *
     * @return string
     */
    public function getParametre()
    {
        return $this->parametre;
    }

//    /**
//     * Set user.
//     *
//     * @param \RS\UserBundle\Entity\User $user
//     *
//     * @return Recherche
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
     * affichage des paramètres.
     *
     * @return string
     */
    public function afficher()
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
