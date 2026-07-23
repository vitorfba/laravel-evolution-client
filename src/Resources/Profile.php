<?php

// src/Resources/Profile.php

namespace Vitorfba\LaravelEvolutionClient\Resources;

use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Models\FetchProfile;
use Vitorfba\LaravelEvolutionClient\Models\PrivacySettings;
use Vitorfba\LaravelEvolutionClient\Models\ProfileName;
use Vitorfba\LaravelEvolutionClient\Models\ProfilePicture;
use Vitorfba\LaravelEvolutionClient\Models\ProfileStatus;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

class Profile
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
     * Create a new Profile resource instance.
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
     * Fetch business profile.
     *
     *
     * @throws EvolutionApiException
     */
    public function fetchBusinessProfile(string $number): array
    {
        $profile = new FetchProfile($number);

        return $this->service->post("/chat/fetchBusinessProfile/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Fetch profile.
     *
     *
     * @throws EvolutionApiException
     */
    public function fetchProfile(string $number): array
    {
        $profile = new FetchProfile($number);

        return $this->service->post("/chat/fetchProfile/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Update profile name.
     *
     *
     * @throws EvolutionApiException
     */
    public function updateProfileName(string $name): array
    {
        $profile = new ProfileName($name);

        return $this->service->post("/chat/updateProfileName/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Update profile status.
     *
     *
     * @throws EvolutionApiException
     */
    public function updateProfileStatus(string $status): array
    {
        $profile = new ProfileStatus($status);

        return $this->service->post("/chat/updateProfileStatus/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Update profile picture.
     *
     *
     * @throws EvolutionApiException
     */
    public function updateProfilePicture(string $picture): array
    {
        $profile = new ProfilePicture($picture);

        return $this->service->post("/chat/updateProfilePicture/{$this->instanceName}", $profile->toArray());
    }

    /**
     * Remove profile picture.
     *
     * @throws EvolutionApiException
     */
    public function removeProfilePicture(): array
    {
        return $this->service->delete("/chat/removeProfilePicture/{$this->instanceName}");
    }

    /**
     * Fetch privacy settings.
     *
     * @throws EvolutionApiException
     */
    public function fetchPrivacySettings(): array
    {
        return $this->service->get("/chat/fetchPrivacySettings/{$this->instanceName}");
    }

    /**
     * Update privacy settings.
     *
     *
     * @throws EvolutionApiException
     */
    public function updatePrivacySettings(
        string $readreceipts,
        string $profile,
        string $status,
        string $online,
        string $last,
        string $groupadd
    ): array {
        $privacy = new PrivacySettings($readreceipts, $profile, $status, $online, $last, $groupadd);

        return $this->service->post("/chat/updatePrivacySettings/{$this->instanceName}", $privacy->toArray());
    }
}
