<?php

// src/Resources/Call.php

namespace Vitorfba\LaravelEvolutionClient\Resources;

use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Models\Call as CallModel;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

class Call
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
     * Create a new Call resource instance.
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
     * Make a fake call.
     *
     *
     * @throws EvolutionApiException
     */
    public function fakeCall(string $number, bool $isVideo = false, int $callDuration = 45): array
    {
        $call = new CallModel($number, $isVideo, $callDuration);

        return $this->service->post("/call/offer/{$this->instanceName}", $call->toArray());
    }
}
