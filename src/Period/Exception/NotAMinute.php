<?php

declare(strict_types=1);

namespace CalendR\Period\Exception;

use CalendR\Exception;

final class NotAMinute extends \InvalidArgumentException implements Exception
{
}
