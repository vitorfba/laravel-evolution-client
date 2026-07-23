<?php

// src/Resources/Group.php

namespace Vitorfba\LaravelEvolutionClient\Resources;

use InvalidArgumentException;
use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;

class Group
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
     * Create a new Group resource instance.
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
     * Get all groups.
     *
     * @param bool $getParticipants Whether to include participants in the response
     *
     * @throws EvolutionApiException
     */
    public function all(bool $getParticipants = false): array
    {
        return $this->service->get("/group/fetchAllGroups/{$this->instanceName}", [
            'getParticipants' => $getParticipants ? 'true' : 'false',
        ]);
    }

    /**
     * Get group info.
     *
     * @param string $groupJid Group remote JID
     *
     * @throws EvolutionApiException
     */
    public function find(string $groupJid): array
    {
        return $this->service->get("/group/findGroupInfos/{$this->instanceName}", [
            'groupJid' => $groupJid,
        ]);
    }

    /**
     * Create a new group.
     *
     * @param string $subject Group subject
     * @param array<int, string> $participants Group members phone numbers with country code
     * @param string $description Group description
     *
     * @throws EvolutionApiException
     */
    public function create(string $subject, array $participants, string $description = ''): array
    {
        // Format participant numbers
        $formattedParticipants = array_map(function ($number) {
            return $this->formatPhoneNumber($number);
        }, $participants);

        return $this->service->post("/group/create/{$this->instanceName}", [
            'subject' => $subject,
            'description' => $description,
            'participants' => $formattedParticipants,
        ]);
    }

    /**
     * Format phone number to be used with the API.
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-digit characters
        return preg_replace('/\D/', '', $phoneNumber);
    }

    /**
     * Update group subject.
     *
     *
     * @throws EvolutionApiException
     */
    public function updateSubject(string $groupJid, string $subject): array
    {
        $query = http_build_query(['groupJid' => $groupJid]);

        return $this->service->post("/group/updateGroupSubject/{$this->instanceName}?{$query}", [
            'subject' => $subject,
        ]);
    }

    /**
     * Update group description.
     *
     * @param string $groupJid Group remote JID
     * @param string $description New group description
     *
     * @throws EvolutionApiException
     */
    public function updateDescription(string $groupJid, string $description): array
    {
        $query = http_build_query(['groupJid' => $groupJid]);

        return $this->service->post("/group/updateGroupDescription/{$this->instanceName}?{$query}", [
            'description' => $description,
        ]);
    }

    /**
     * Add participants to a group.
     *
     * @param string $groupJid Group remote JID
     * @param array<int, string> $participants Phone numbers/JIDs to add
     *
     * @throws EvolutionApiException
     */
    public function addParticipants(string $groupJid, array $participants): array
    {
        return $this->updateParticipant($groupJid, 'add', $participants);
    }

    /**
     * Remove participants from a group.
     *
     * @param string $groupJid Group remote JID
     * @param array<int, string> $participants Phone numbers/JIDs to remove
     *
     * @throws EvolutionApiException
     */
    public function removeParticipants(string $groupJid, array $participants): array
    {
        return $this->updateParticipant($groupJid, 'remove', $participants);
    }

    /**
     * Make a participant an admin.
     *
     * @param string $groupJid Group remote JID
     * @param string $participant Phone number/JID to promote
     *
     * @throws EvolutionApiException
     */
    public function promoteToAdmin(string $groupJid, string $participant): array
    {
        return $this->updateParticipant($groupJid, 'promote', [$participant]);
    }

    /**
     * Demote a participant from admin.
     *
     * @param string $groupJid Group remote JID
     * @param string $participant Phone number/JID to demote
     *
     * @throws EvolutionApiException
     */
    public function demoteFromAdmin(string $groupJid, string $participant): array
    {
        return $this->updateParticipant($groupJid, 'demote', [$participant]);
    }

    /**
     * Leave a group.
     *
     * @param string $groupJid Group remote JID
     *
     * @throws EvolutionApiException
     */
    public function leave(string $groupJid): array
    {
        return $this->service->delete("/group/leaveGroup/{$this->instanceName}", [
            'groupJid' => $groupJid,
        ]);
    }

    /**
     * Get group invite code.
     *
     * @param string $groupJid Group remote JID
     *
     * @throws EvolutionApiException
     */
    public function getInviteCode(string $groupJid): array
    {
        return $this->service->get("/group/inviteCode/{$this->instanceName}", [
            'groupJid' => $groupJid,
        ]);
    }

    /**
     * Join a group using invite code.
     *
     * @param string $inviteCode Group invite code
     *
     * @throws EvolutionApiException
     */
    public function joinWithInviteCode(string $inviteCode): array
    {
        return $this->service->get("/group/acceptInviteCode/{$this->instanceName}", [
            'inviteCode' => $inviteCode,
        ]);
    }

    /**
     * Get group participants.
     *
     *
     * @throws EvolutionApiException
     */
    public function getParticipants(string $groupJid): array
    {
        return $this->service->get("/group/participants/{$this->instanceName}", [
            'groupJid' => $groupJid,
        ]);
    }

    /**
     * Update participant in a group (add, remove, promote, demote).
     *
     * @param string $action Enum: 'add', 'remove', 'promote', 'demote'
     * @param array $participants Array of phone numbers/JIDs
     *
     * @throws EvolutionApiException
     */
    public function updateParticipant(string $groupJid, string $action, array $participants): array
    {
        $formattedParticipants = array_map(function ($number) {
            return $this->formatPhoneNumber($number);
        }, $participants);

        $query = http_build_query(['groupJid' => $groupJid]);

        return $this->service->post("/group/updateParticipant/{$this->instanceName}?{$query}", [
            'action' => $action,
            'participants' => $formattedParticipants,
        ]);
    }

    /**
     * Update the group profile picture.
     *
     * @param string $groupJid Group remote JID
     * @param string $image New profile picture image URL
     *
     * @throws EvolutionApiException
     */
    public function updateGroupPicture(string $groupJid, string $image): array
    {
        $query = http_build_query(['groupJid' => $groupJid]);

        return $this->service->post("/group/updateGroupPicture/{$this->instanceName}?{$query}", [
            'image' => $image,
        ]);
    }

    /**
     * Revoke the current group invite code.
     *
     * @param string $groupJid Group remote JID
     *
     * @throws EvolutionApiException
     */
    public function revokeInviteCode(string $groupJid): array
    {
        $query = http_build_query(['groupJid' => $groupJid]);

        return $this->service->post("/group/revokeInviteCode/{$this->instanceName}?{$query}");
    }

    /**
     * Get information about a group from its invite code.
     *
     * @param string $inviteCode Group invite code
     *
     * @throws EvolutionApiException
     */
    public function inviteInfo(string $inviteCode): array
    {
        return $this->service->get("/group/inviteInfo/{$this->instanceName}", [
            'inviteCode' => $inviteCode,
        ]);
    }

    /**
     * Send a group invitation to a list of numbers.
     *
     * @param string $groupJid Group remote JID
     * @param string $description Description to send with the invitation
     * @param array<int, string> $numbers Numbers to receive the invitation
     *
     * @throws EvolutionApiException
     */
    public function sendInvite(string $groupJid, string $description, array $numbers): array
    {
        $formattedNumbers = array_map(function ($number) {
            return $this->formatPhoneNumber($number);
        }, $numbers);

        return $this->service->post("/group/sendInvite/{$this->instanceName}", [
            'groupJid' => $groupJid,
            'description' => $description,
            'numbers' => $formattedNumbers,
        ]);
    }

    /**
     * Toggle ephemeral (disappearing) messages for a group.
     *
     * @param string $groupJid Group remote JID
     * @param int $expiration Time to expire messages, in seconds
     *
     * @throws EvolutionApiException
     */
    public function toggleEphemeral(string $groupJid, int $expiration): array
    {
        $query = http_build_query(['groupJid' => $groupJid]);

        return $this->service->post("/group/toggleEphemeral/{$this->instanceName}?{$query}", [
            'expiration' => $expiration,
        ]);
    }

    /**
     * Update a group setting.
     *
     * @param string $groupJid Group remote JID
     * @param string $action Setting: announcement|not_announcement|locked|unlocked
     *
     * @throws EvolutionApiException
     * @throws InvalidArgumentException
     */
    public function updateSetting(string $groupJid, string $action): array
    {
        $allowed = ['announcement', 'not_announcement', 'locked', 'unlocked'];

        if (! in_array($action, $allowed, true)) {
            throw new InvalidArgumentException('Action must be one of: ' . implode(', ', $allowed));
        }

        $query = http_build_query(['groupJid' => $groupJid]);

        return $this->service->post("/group/updateSetting/{$this->instanceName}?{$query}", [
            'action' => $action,
        ]);
    }
}
