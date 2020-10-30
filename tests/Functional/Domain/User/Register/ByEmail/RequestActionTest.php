<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User\Register\ByEmail;

use App\DataFixtures\Helper\UserFixtures;
use App\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\JsonResponse;

class RequestActionTest extends AbstractTest
{
    private $email =  'email@email.eamil';
    private $password = '12345678Ab';

    protected $method = 'POST';
    protected $uri = '/user/register/email/request/';

    public function getFixtures() : array
    {
        return [UserFixtures::class];
    }

    public function testRegisterValid() : void
    {
        $this->makeRequest([
            'email' => $this->email,
            'password' => $this->password,
            'plainPassword' => $this->password
        ]);

        self::assertResponseOk($this->response);
        self::assertArrayHasKey('token', $this->content);
        self::assertArrayHasKey('status', $this->content);
        self::assertArrayHasKey('refreshToken', $this->content);
        self::assertArrayHasKey('role', $this->content);
        self::assertArrayHasKey('email', $this->content);
        self::assertEquals($this->email, $this->content['email']);
        self::assertEquals('WAIT', $this->content['status']);
    }

    /**
     * @dataProvider getInvalidCredential
     */
    public function testInvalidValue(string $password) : void
    {
        $this->makeRequest([
            'email' => $password ? $password : '',
            'password' => $password,
            'plainPassword' => ''
        ]);

        self::assertResponseCode(JsonResponse::HTTP_NOT_FOUND, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('email', $this->content['errors']);
        self::assertArrayHasKey('password', $this->content['errors']);
        self::assertArrayHasKey('plainPassword', $this->content['errors']);
    }

    public function getInvalidCredential()
    {
        return [ [''], ['123'], ['Ab1'], ['freshManF'], ['12345678A'] ];
    }

    /**
     * тестирование ункиальности email'a пользователя
     */
    public function testRegisterUnique() : void
    {
        $this->makeRequest([
            'email' => getenv('TEST_USER_EMAIL'),
            'password' => '12345678Ab',
            'plainPassword' => '12345678Ab'
        ]);

        self::assertResponseCode(JsonResponse::HTTP_NOT_FOUND, $this->response);
        self::assertArrayHasKey('errors', $this->content);
        self::assertArrayHasKey('email', $this->content['errors']);
    }
}
