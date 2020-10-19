<?php

declare(strict_types=1);

namespace App\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class HTTPExceptionListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event) : void
    {
        $error = $event->getThrowable();
        $this->logger->critical($error->getMessage());
        if ($error instanceof ApplicationException) {
            $response = $this->handleKnownExceptions($error);
        } elseif ($error instanceof AccessDeniedHttpException || $error instanceof NotFoundHttpException) {
            $response = $this->handle404($error);
        } else {
            $response = $this->handleUnknownExceptions($error);
        }
        $response->headers->set('Content-Type', 'application/json');
        $event->setResponse($response);
    }

    private function handle404(Throwable $exception)
    {
        return new Response(
            $exception->getMessage(),
            Response::HTTP_NOT_FOUND
        );
    }

    private function handleKnownExceptions(Throwable $exception)
    {
        return new Response($exception->getMessage(), $exception->getStatusCode());
    }

    private function handleUnknownExceptions(Throwable $exception) : Response
    {
        try {
            //$statusCode = $exception->getStatusCode();
        } catch (\Exception $exception) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }
        $statusCode = Response::HTTP_NOT_FOUND;
        return new Response($exception->getMessage(), $statusCode);
    }
}
