<?php

declare(strict_types=1);

namespace App\Service;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

class SerializeService
{
    private $serializer;

    private const JSON = 'json';

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function deserialize(Request $request, string $className, $groups = [], $format = self::JSON)
    {
        if (!empty($groups)) {
            $context = new DeserializationContext();
            $context->setGroups($groups);
            return $this->serializer->deserialize($request->getContent(), $className, $format, $context);
        }
        return $this->serializer->deserialize($request->getContent(), $className, $format);
    }

    public function serialize($serializedObject = ['success' => true], $groups = [], $format = self::JSON)
    {
        if (!empty($groups)) {
            $context = new SerializationContext();
            $context->setGroups($groups);
            return $this->serializer->serialize($serializedObject, $format, $context);
        }
        return $this->serializer->serialize($serializedObject, $format);
    }
}
