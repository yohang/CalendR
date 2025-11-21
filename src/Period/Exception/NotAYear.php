<?php

declare(strict_types=1);

namespace CalendR\Period\Exception;

use CalendR\Exception;

class NotAYear extends \InvalidArgumentException implements Exception
{
}
