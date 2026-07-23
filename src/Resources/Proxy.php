<?php

// src/Resources/Proxy.php

namespace Vitorfba\LaravelEvolutionClient\Resources;

use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Models\Proxy as ProxyModel;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

class Proxy
{
    /**
     * @var EvolutionService The Evolution service
     */
    protected EvolutionService $service;

    /**
     * @var string The instance name
     */
    protected string $instanceName;

    /**
     * Create a new Proxy resource instance.
     */
    public function __construct(EvolutionService $service, string $instanceName)
    {
        $this->service = $service;
        $this->instanceName = $instanceName;
    }

    /**
     * Get the instance name.
     */
    public function getInstanceName(): string
    {
        return $this->instanceName;
    }

    /**
     * Set the instance name.
     */
    public function setInstanceName(string $instanceName): void
    {
        $this->instanceName = $instanceName;
    }

    /**
     * Set a proxy for the instance.
     *
     *
     * @throws EvolutionApiException
     */
    public function set(
        bool $enabled,
        string $host,
        string $port,
        string $protocol,
        ?string $username = null,
        ?string $password = null
    ): array {
        $proxy = new ProxyModel($enabled, $host, $port, $protocol, $username, $password);

        return $this->service->post("/proxy/set/{$this->instanceName}", $proxy->toArray());
    }

    /**
     * Find proxy settings for the instance.
     *
     * @throws EvolutionApiException
     */
    public function find(): array
    {
        return $this->service->get("/proxy/find/{$this->instanceName}");
    }
}
