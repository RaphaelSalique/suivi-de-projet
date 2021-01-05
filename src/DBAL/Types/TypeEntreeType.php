<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * types d'entrée.
 */
class TypeEntreeType extends AbstractEnumType
{
    const INCIDENT          = 'INCIDENT';
    const INFORMATION   = 'INFORMATION';
    const PROCESS          = 'PROCESS';
    const SUGGESTION    = 'SUGGESTION';

    /**
     * @var array Readable choices
     * @static
     */
    protected static $choices = [
        self::INCIDENT          => 'Incident',
        self::INFORMATION   => 'Information',
        self::PROCESS          => 'Process',
        self::SUGGESTION    => 'Suggestion',
    ];
}
