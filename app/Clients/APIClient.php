<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;

class APIClient
{
    protected string $baseUrl;

    public function __construct(string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? config('app.url') . '/api/';
    }

    /**
     * Make an API request
     *
     * @param string $method GET, POST, PUT, DELETE, PATCH
     * @param string $endpoint API endpoint
     * @param array $data Request data
     * @param array $headers Optional headers
     * @return array
     */
    public function request(string $method, string $endpoint, array $data = [], array $headers = []): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
        $method = strtolower($method);

        if (!in_array($method, ['get','post','put','delete','patch'])) {
            throw new \Exception("Unsupported HTTP method: $method");
        }

        $response = Http::withHeaders($headers)->$method($url, $data);

        return $response->json();
    }

    /**
     * Shortcut methods
     */
    public function get(string $endpoint, array $data = [], array $headers = []): array
    {
        echo "A";die;
        return $this->request('GET', $endpoint, $data, $headers);
    }

    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('POST', $endpoint, $data, $headers);
    }

    public function put(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('PUT', $endpoint, $data, $headers);
    }

    public function delete(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('DELETE', $endpoint, $data, $headers);
    }
}
