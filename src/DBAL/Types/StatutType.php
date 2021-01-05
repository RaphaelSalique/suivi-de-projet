<?php

namespace App\DBAL\Types;

//use Fresh\Bundle\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * statuts.
 */
class StatutType// extends AbstractEnumType
{
    const ANNULE = 'ANNULE';
    const FERME   = 'FERME';
    const OUVERT = 'OUVERT';

    /**
     * @var array Readable choices
     * @static
     */
    protected static $choices = [
        self::ANNULE => 'Annulé',
        self::FERME   => 'Fermé',
        self::OUVERT => 'Ouvert',
    ];
}
