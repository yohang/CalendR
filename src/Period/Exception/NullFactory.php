<?php

declare(strict_types=1);

namespace CalendR\Period\Exception;

use CalendR\Exception;

final class NullFactory extends \RuntimeException implements Exception
{
    public function __construct()
    {
        parent::__construct('The period factory is not initialized. You must set a factory before creating periods.');
    }
}
