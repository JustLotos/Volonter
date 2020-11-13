<?php

namespace App\Tests;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    private $crawler;

    public function __construct()
    {
        $this->crawler = new Crawler();
    }

    public function isTitleExist(Response $response, string $type = 'text/html'): bool
    {
        $this->crawler->addContent($response->getContent(), $type);
        return (bool)count($this->crawler->filter('title'));
    }

    public function parseTitle(Response $response)
    {
        $add = '';
        $content = $response->getContent();
        if ($response->headers->get('Content-Type') === 'application/json') {
            $data = json_decode($content);
            if ($data) {
                $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $add = ' FORMATTED';
            }
        }

        return '[' . $response->getStatusCode() . ']' . $add . ' - ' . $content;
    }


    public function assertResponseOk(Response $response) : void
    {
        var_dump($this->response); die();
        $this->failOnResponseStatusCheck($response, 'isOk');
    }

    public function assertResponseRedirect(Response $response) : void
    {
        $this->failOnResponseStatusCheck($response, 'isRedirect');
    }

    public function assertResponseNotFound(Response $response) : void
    {
        $this->failOnResponseStatusCheck($response, 'isNotFound');
    }

    public function assertResponseForbidden(?Response $response) : void
    {
        $this->failOnResponseStatusCheck($response, 'isForbidden');
    }

    public function assertResponseCode(Response $response, int $expectedCode) : void
    {
        $this->failOnResponseStatusCheck($response, $expectedCode);
    }

    public function guessErrorMessageFromResponse(Response $response, string $type) : string
    {
        try {
            if (!$this->isTitleExist($response, $type)) {
                $title = $this->parseTitle($response);
            } else {
                $title = $this->crawler->filter('title')->text();
            }
        } catch (\Throwable $e) {
            $title = $e->getMessage();
        }

        return trim($title);
    }

    private function failOnResponseStatusCheck(
        Response $response,
        $callback = 'isOk',
        ?string $message = null,
        string $type = 'text/html'
    ) : ?string {


        var_dump($this->response); die();

        try {
            if (is_int($callback)) {
                $this->assertEquals($callback, $response->getStatusCode());
                var_dump(321);
            } else {
                var_dump($response->getContent());
                $this->assertTrue($response->{$callback}());
            }

            return null;
        } catch (\Throwable $e) {
            // nothing to do
        }

        $err = $this->guessErrorMessageFromResponse($response, $type);
        if ($message) {
            $message = rtrim($message, '.') . '. ';
        }

        var_dump(123);
        if (is_int($callback)) {
            $template = 'Failed asserting Response status code %s equals %s.';
        } else {
            $template = 'Failed asserting that Response[%s] %s.';
            $callback = preg_replace('#([a-z])([A-Z])#', '$1 $2', $callback);
        }
        var_dump(123);

        $message .= sprintf($template, $response->getStatusCode(), $callback, $err);
        $max_length = 100;
        if (mb_strlen($err, 'utf-8') < $max_length) {
            $message .= ' ' . $this->makeErrorOneLine($err);
        } else {
            $message .= ' ' . $this->makeErrorOneLine(mb_substr($err, 0, $max_length, 'utf-8') . '...');
            $message .= "\n\n" . $err;
        }

        return $message;
    }

    private function makeErrorOneLine(string $text)
    {
        return preg_replace('#[\n\r]+#', ' ', $text);
    }
}