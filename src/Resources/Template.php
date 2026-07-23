<?php

// src/Resources/Template.php

namespace Vitorfba\LaravelEvolutionClient\Resources;

use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

class Template
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
     * Create a new Template resource instance.
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
     * Create a template.
     *
     *
     * @throws EvolutionApiException
     */
    public function create(
        string $name,
        string $category,
        string $language,
        array $components,
        bool $allowCategoryChange = false,
        ?string $webhookUrl = null
    ): array {
        $data = [
            'name' => $name,
            'category' => $category,
            'allowCategoryChange' => $allowCategoryChange,
            'language' => $language,
            'components' => $components,
        ];

        if ($webhookUrl !== null) {
            $data['webhookUrl'] = $webhookUrl;
        }

        return $this->service->post("/template/create/{$this->instanceName}", $data);
    }

    /**
     * Find templates.
     *
     * @throws EvolutionApiException
     */
    public function find(): array
    {
        return $this->service->get("/template/find/{$this->instanceName}");
    }

    /**
     * Edit an existing template.
     *
     *
     * @throws EvolutionApiException
     */
    public function edit(string $name, array $components): array
    {
        return $this->service->post("/template/edit/{$this->instanceName}", [
            'name' => $name,
            'components' => $components,
        ]);
    }

    /**
     * Delete a template.
     *
     *
     * @throws EvolutionApiException
     */
    public function delete(string $name): array
    {
        return $this->service->delete("/template/delete/{$this->instanceName}", [
            'name' => $name,
        ]);
    }
}
