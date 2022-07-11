<?php

declare(strict_types=1);

namespace BenTools\Shh\Tests\Classes;

use Symfony\Component\HttpKernel\Kernel;

if (Kernel::MAJOR_VERSION <= 5) {
    require_once __DIR__.'/TestKernelLegacy.php';
} else {
    require_once __DIR__.'/TestKernel6.php';
}
