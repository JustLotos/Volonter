<?php

declare(strict_types=1);

namespace App\Service\MailService;

use App\Service\TwigService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailBuilderService
{
    private $twig;
    private $params;

    public function __construct(TwigService $twig)
    {
        $this->twig = $twig;
        $this->params = [];
    }

    public function build(string $path)
    {
        return $this->twig->render($path, $this->params);
    }

    public function setParam(string $name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }
}
