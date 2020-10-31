<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ValidateService;
use Symfony\Component\HttpFoundation\Response;
use App\Service\SerializeService;

trait ControllerHelper
{
    private $serializer;

    private $validator;

    public function __construct(SerializeService $serializer, ValidateService $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function response(string $content, int $statusCode = Response::HTTP_OK): Response
    {
        return new Response($content, $statusCode);
    }

    public function getSimpleSuccessResponse()
    {
        return json_encode(['success' => true]);
    }

    public function getSimpleErrorResponse(array $errors)
    {
        return json_encode(['success' => false, 'errors'=>json_encode($errors)]);
    }
}
