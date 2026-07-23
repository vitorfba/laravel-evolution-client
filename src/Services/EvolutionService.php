<?php

// src/Services/EvolutionService.php

namespace Vitorfba\LaravelEvolutionClient\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;

class EvolutionService
{
    /**
     * @var Client The HTTP client
     */
    protected Client $client;

    /**
     * @var string The base URL for the Evolution API
     */
    protected string $baseUrl;

    /**
     * @var string The API key
     */
    protected string $apiKey;

    /**
     * @var string The integration engine
     */
    protected string $integration;

    /**
     * Create a new EvolutionService instance.
     */
    public function __construct(string $baseUrl, string $apiKey, int $timeout = 30)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $timeout,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'apikey' => $this->apiKey,
            ],
        ]);
    }

    /**
     * Get the base URL.
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get the API key.
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Get the HTTP client.
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Make a GET request to the Evolution API.
     *
     *
     * @throws EvolutionApiException
     */
    public function get(string $endpoint, array $queryParams = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $queryParams]);
    }

    /**
     * Make a request to the Evolution API.
     *
     *
     * @throws EvolutionApiException
     */
    protected function request(string $method, string $endpoint, array $options = []): array
    {
        $url = ltrim($endpoint, '/');

        try {
            $response = $this->client->request($method, $url, $options);
            $body = trim((string) $response->getBody()->getContents());

            // DELETE/logout often returns an empty body with 2xx — treat as success.
            if ($body === '' || strcasecmp($body, 'null') === 0) {
                return [];
            }

            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new EvolutionApiException('Invalid JSON response from API', 500);
            }

            if (! is_array($data)) {
                return [];
            }

            if ($this->responseIndicatesError($data)) {
                $message = $this->extractErrorMessage($data);
                $code = is_numeric($data['code'] ?? null) ? (int) $data['code'] : 400;

                throw new EvolutionApiException($message, $code);
            }

            return $data;
        } catch (EvolutionApiException $e) {
            // Do not re-wrap domain exceptions as "Unexpected error".
            throw $e;
        } catch (GuzzleException $e) {
            $message = $e->getMessage();
            $statusCode = $e->getCode();

            if ($e instanceof RequestException && $e->hasResponse()) {
                $errorBody = $e->getResponse()->getBody()->getContents();
                $errorData = json_decode($errorBody, true);

                if (is_array($errorData) && $this->responseIndicatesError($errorData)) {
                    $message = $this->extractErrorMessage($errorData);
                }
            }

            throw new EvolutionApiException((string) $message, (int) $statusCode, $e);
        } catch (Exception $e) {
            throw new EvolutionApiException('Unexpected error: ' . $e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    /**
     * Evolution frequently returns `"error": false` on success (e.g. logout).
     * `isset($data['error'])` is true for false, so we must ignore falsy error values.
     *
     * @param array<string, mixed> $data
     */
    protected function responseIndicatesError(array $data): bool
    {
        if (array_key_exists('error', $data)) {
            $error = $data['error'];

            if ($error === false || $error === null || $error === 0 || $error === '0' || $error === '') {
                return false;
            }

            if (is_string($error) && strcasecmp($error, 'false') === 0) {
                return false;
            }

            return true;
        }

        $status = $data['status'] ?? null;

        return is_string($status) && strcasecmp($status, 'error') === 0;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function extractErrorMessage(array $data): string
    {
        $error = $data['error'] ?? null;

        if (is_string($error) && trim($error) !== '') {
            return $error;
        }

        if (is_array($error)) {
            $nested = $error['message'] ?? $error['error'] ?? null;
            if (is_string($nested) && trim($nested) !== '') {
                return $nested;
            }
        }

        $message = $data['message'] ?? null;
        if (is_string($message) && trim($message) !== '') {
            return $message;
        }

        return 'Unknown API error';
    }

    /**
     * Make a POST request to the Evolution API.
     *
     *
     * @throws EvolutionApiException
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    /**
     * Make a PUT request to the Evolution API.
     *
     *
     * @throws EvolutionApiException
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * Make a DELETE request to the Evolution API.
     *
     *
     * @throws EvolutionApiException
     */
    public function delete(string $endpoint, array $queryParams = []): array
    {
        return $this->request('DELETE', $endpoint, ['query' => $queryParams]);
    }
}
