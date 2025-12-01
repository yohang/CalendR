<?php

declare(strict_types=1);

namespace CalendR\Period\Exception;

use CalendR\Exception;

final class NotAWeek extends \InvalidArgumentException implements Exception
{
}
