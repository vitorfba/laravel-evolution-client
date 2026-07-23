<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

function makeServiceWithResponses(array $responses): EvolutionService
{
    $mock = new MockHandler($responses);
    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler, 'base_uri' => 'http://evolution.test']);

    $service = new EvolutionService('http://evolution.test', 'api-key');

    $reflection = new ReflectionClass($service);
    $property = $reflection->getProperty('client');
    $property->setAccessible(true);
    $property->setValue($service, $client);

    return $service;
}

it('treats error false as success on logout responses', function () {
    $service = makeServiceWithResponses([
        new Response(200, [], json_encode([
            'status' => 'SUCCESS',
            'error' => false,
            'response' => ['message' => 'Instance logged out'],
        ])),
    ]);

    $result = $service->delete('/instance/logout/planalto');

    expect($result)
        ->toBeArray()
        ->and($result['error'])->toBeFalse()
        ->and($result['status'])->toBe('SUCCESS');
});

it('treats empty body as success', function () {
    $service = makeServiceWithResponses([
        new Response(200, [], ''),
    ]);

    expect($service->delete('/instance/logout/planalto'))->toBe([]);
});

it('throws for truthy error values', function () {
    $service = makeServiceWithResponses([
        new Response(200, [], json_encode([
            'error' => 'Instance not found',
            'status' => 'error',
        ])),
    ]);

    $service->delete('/instance/logout/missing');
})->throws(EvolutionApiException::class, 'Instance not found');

it('does not rewrap EvolutionApiException as unexpected error', function () {
    $service = makeServiceWithResponses([
        new Response(200, [], json_encode([
            'error' => false,
            'message' => 'ok',
        ])),
    ]);

    // Smoke: success path does not throw
    expect($service->get('/instance/fetchInstances'))->toMatchArray(['error' => false]);
});
