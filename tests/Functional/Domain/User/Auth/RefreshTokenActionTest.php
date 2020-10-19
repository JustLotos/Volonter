<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Auth;

use App\DataFixtures\User\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenActionTest extends AbstractTest
{
    protected $method = 'POST';
    protected $uri = '/auth/token/refresh/';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testValid() : void
    {
        $clientAuth = $this->createAuthenticatedClient();

        /** @var Response $response */
        $response = $clientAuth->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->makeRequest([ 'refreshToken'=> $content['refreshToken']]);

        $this->assertResponseOk($this->response);
        static::assertArrayHasKey('token', $this->content);
        static::assertArrayHasKey('refreshToken', $this->content);
    }

    public function testInvalidValue() : void
    {
        $this->makeRequest(['refreshToken'=> '123123']);
        $this->assertResponseCode(Response::HTTP_UNAUTHORIZED, $this->response);
        static::assertArrayHasKey('errors', $this->content);
        static::assertArrayHasKey('auth', $this->content['errors']);
    }
}
