<?php

declare(strict_types=1);

namespace CalendR\Event\Exception;

use CalendR\Exception;

final class NoProviderFound extends \OutOfBoundsException implements Exception
{
}
