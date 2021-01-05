<?php

namespace App\DBAL\Types;

//use Fresh\Bundle\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * sévérité.
 */
class SeveriteType// extends AbstractEnumType
{
    const NA                 = 'NA';
    const BASSE          = 'BASSE';
    const MOYENNE     = 'MOYENNE';
    const HAUTE          = 'HAUTE';
    const BLOQUANTE = 'BLOQUANTE';

    /**
     * @var array Readable choices
     * @static
     */
    protected static $choices = [
        self::NA    => '1 N/A',
        self::BASSE          => '2 Basse',
        self::MOYENNE    => '3 Moyenne',
        self::HAUTE          => '4 Haute',
        self::BLOQUANTE   => '5 Bloquante',
    ];
}
