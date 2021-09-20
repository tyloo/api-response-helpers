<?php declare(strict_types=1);

namespace Tyloo\Tests;

use DomainException;
use JsonException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tyloo\ApiResponseHelpers;

class ApiResponseTestersTest extends TestCase
{
    protected object $service;

    protected function setUp(): void
    {
        $this->service = $this->getObjectForTrait(ApiResponseHelpers::class);
        parent::setUp();
    }

    /**
     * @dataProvider basicResponsesDataProvider
     * @throws JsonException
     */
    public function testResponses(string $method, array $args, int $code, array $data): void
    {
        /** @var JsonResponse $response */
        $response = \call_user_func_array([$this->service, $method], $args);
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals($code, $response->getStatusCode());
        self::assertEquals($data, json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR));
        self::assertJsonStringEqualsJsonString(json_encode($data, \JSON_THROW_ON_ERROR), $response->getContent());
    }

    public function basicResponsesDataProvider(): iterable
    {
        return [
            'respondNotFound()' => [
                'respondNotFound',
                ['Ouch'],
                Response::HTTP_NOT_FOUND,
                ['error' => 'Ouch'],
            ],

            'respondNotFound() with custom key' => [
                'respondNotFound',
                ['Ouch', 'message'],
                Response::HTTP_NOT_FOUND,
                ['message' => 'Ouch'],
            ],

            'respondNotFound() with exception and custom key' => [
                'respondNotFound',
                [
                    new DomainException('Unknown model'),
                    'message'
                ],
                Response::HTTP_NOT_FOUND,
                ['message' => 'Unknown model'],
            ],

            'respondWithSuccess(), default response data' => [
                'respondWithSuccess',
                [],
                Response::HTTP_OK,
                ['success' => true],
            ],

            'respondWithSuccess(), custom response data' => [
                'respondWithSuccess',
                [['order' => 237]],
                Response::HTTP_OK,
                ['order' => 237],
            ],

            'respondWithSuccess(), nested custom response data' => [
                'respondWithSuccess',
                [['order' => 237, 'customer' => ['name' => 'Jason Bourne']]],
                Response::HTTP_OK,
                ['order' => 237, 'customer' => ['name' => 'Jason Bourne']],
            ],

            'respondOk()' => [
                'respondOk',
                ['Order accepted'],
                Response::HTTP_OK,
                ['success' => 'Order accepted'],
            ],

            'respondUnAuthenticated(), default message' => [
                'respondUnAuthenticated',
                [],
                Response::HTTP_UNAUTHORIZED,
                ['error' => 'Unauthenticated'],
            ],

            'respondUnAuthenticated(), custom message' => [
                'respondUnAuthenticated',
                ['Denied'],
                Response::HTTP_UNAUTHORIZED,
                ['error' => 'Denied'],
            ],

            'respondForbidden(), default message' => [
                'respondForbidden',
                [],
                Response::HTTP_FORBIDDEN,
                ['error' => 'Forbidden'],
            ],

            'respondForbidden(), custom message' => [
                'respondForbidden',
                ['Disavowed'],
                Response::HTTP_FORBIDDEN,
                ['error' => 'Disavowed'],
            ],

            'respondError(), default message' => [
                'respondError',
                [],
                Response::HTTP_BAD_REQUEST,
                ['error' => 'Error'],
            ],

            'respondError(), custom message' => [
                'respondError',
                ['Order tampering detected'],
                Response::HTTP_BAD_REQUEST,
                ['error' => 'Order tampering detected'],
            ],

            'respondCreated()' => [
                'respondCreated',
                [],
                Response::HTTP_CREATED,
                [],
            ],

            'respondCreated() with response data' => [
                'respondCreated',
                [['user' => ['name' => 'Jet Li']]],
                Response::HTTP_CREATED,
                ['user' => ['name' => 'Jet Li']],
            ],

            'respondFailedValidation()' => [
                'respondFailedValidation',
                ['An invoice is required'],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['message' => 'An invoice is required'],
            ],

            'respondNoContent()' => [
                'respondNoContent',
                [],
                Response::HTTP_NO_CONTENT,
                [],
            ],

            'respondNoContent() with data' => [
                'respondNoContent',
                [['role' => 'manager']],
                Response::HTTP_NO_CONTENT,
                ['role' => 'manager'],
            ],
        ];
    }
}
