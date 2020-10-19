<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigService extends Environment
{
    public function __construct(KernelInterface $kernel)
    {
        $loader = new FilesystemLoader($kernel->getProjectDir() . '/templates');
        parent::__construct($loader);
    }
}
