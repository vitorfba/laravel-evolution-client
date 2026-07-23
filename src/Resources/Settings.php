<?php

// src/Resources/Settings.php

namespace Vitorfba\LaravelEvolutionClient\Resources;

use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Models\Settings as SettingsModel;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

class Settings
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
     * Create a new Settings resource instance.
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
     * Set instance settings.
     *
     *
     * @throws EvolutionApiException
     */
    public function set(
        bool $rejectCall = false,
        ?string $msgCall = null,
        bool $groupsIgnore = false,
        bool $alwaysOnline = false,
        bool $readMessages = false,
        bool $syncFullHistory = false,
        bool $readStatus = false
    ): array {
        $settings = new SettingsModel(
            $rejectCall,
            $msgCall,
            $groupsIgnore,
            $alwaysOnline,
            $readMessages,
            $syncFullHistory,
            $readStatus
        );

        return $this->service->post("/settings/set/{$this->instanceName}", $settings->toArray());
    }

    /**
     * Find instance settings.
     *
     * @throws EvolutionApiException
     */
    public function find(): array
    {
        return $this->service->get("/settings/find/{$this->instanceName}");
    }
}
