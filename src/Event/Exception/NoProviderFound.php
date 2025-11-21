<?php

declare(strict_types=1);

namespace CalendR\Event\Exception;

use CalendR\Exception;

class NoProviderFound extends \OutOfBoundsException implements Exception
{
}
