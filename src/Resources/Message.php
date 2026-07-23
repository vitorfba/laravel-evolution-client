<?php

// src/Resources/Message.php

namespace Vitorfba\LaravelEvolutionClient\Resources;

use Vitorfba\LaravelEvolutionClient\Exceptions\EvolutionApiException;
use Vitorfba\LaravelEvolutionClient\Models\ButtonMessage;
use Vitorfba\LaravelEvolutionClient\Models\Contact;
use Vitorfba\LaravelEvolutionClient\Models\ContactMessage;
use Vitorfba\LaravelEvolutionClient\Models\ListMessage;
use Vitorfba\LaravelEvolutionClient\Models\PollMessage;
use Vitorfba\LaravelEvolutionClient\Models\ReactionMessage;
use Vitorfba\LaravelEvolutionClient\Models\StatusMessage;
use Vitorfba\LaravelEvolutionClient\Models\TemplateMessage;
use Vitorfba\LaravelEvolutionClient\Models\TextMessage;
use Vitorfba\LaravelEvolutionClient\Services\EvolutionService;
use InvalidArgumentException;

class Message
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
     * Create a new Message resource instance.
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

    // src/Resources/Message.php - Modify sendText method

    /**
     * Send a text message.
     *
     *
     * @throws EvolutionApiException
     * @throws InvalidArgumentException
     */
    public function sendText(
        string $phoneNumber,
        string $message,
        bool $isGroup = false,
        ?int $delay = null,
        ?bool $linkPreview = null,
        ?bool $mentionsEveryOne = null,
        ?array $mentioned = null
    ): array {
        if (empty($phoneNumber)) {
            throw new InvalidArgumentException('Phone number is required');
        }

        if (empty($message)) {
            throw new InvalidArgumentException('Message text is required');
        }

        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $textMessage = new TextMessage(
            $recipient,
            $message,
            $delay,
            null,
            $linkPreview,
            $mentionsEveryOne,
            $mentioned
        );

        return $this->service->post("/message/sendText/{$this->instanceName}", $textMessage->toArray());
    }

    /**
     * Format phone number to be used with the API.
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-digit characters
        $number = preg_replace('/\D/', '', $phoneNumber);

        // Add @ to create a valid recipient id for the API
        return $number . '@c.us';
    }

    /**
     * Send an image message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendImage(string $phoneNumber, string $image, string $caption = '', bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/image/{$this->instanceName}", [
            'number' => $recipient,
            'options' => [
                'delay' => 1200,
                'presence' => 'composing',
            ],
            'imageMessage' => [
                'image' => $image,
                'caption' => $caption,
            ],
        ]);
    }

    /**
     * Send a document message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendDocument(string $phoneNumber, string $document, string $fileName, string $caption = '', bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/document/{$this->instanceName}", [
            'number' => $recipient,
            'options' => [
                'delay' => 1200,
                'presence' => 'composing',
            ],
            'documentMessage' => [
                'document' => $document,
                'fileName' => $fileName,
                'caption' => $caption,
            ],
        ]);
    }

    /**
     * Send a location message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendLocation(string $phoneNumber, float $latitude, float $longitude, string $name = '', string $address = '', bool $isGroup = false, int $delay = 0): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $payload = [
            'number' => $recipient,
            'name' => $name,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        if ($delay > 0) {
            $payload['delay'] = $delay;
        }

        return $this->service->post("/message/sendLocation/{$this->instanceName}", $payload);
    }

    /**
     * Send a media message (image, video or document).
     *
     * @param string $phoneNumber Recipient phone number or group id
     * @param string $mediatype Media type: image, video or document
     * @param string $mimetype MIME type, e.g. image/png
     * @param string $caption Media caption
     * @param string $media URL or base64 media content
     * @param string $fileName File name, e.g. image.png
     * @param int $delay Presence time in milliseconds before sending
     * @param bool $isGroup Whether the recipient is a group
     *
     * @throws EvolutionApiException
     */
    public function sendMedia(
        string $phoneNumber,
        string $mediatype,
        string $mimetype,
        string $caption,
        string $media,
        string $fileName,
        int $delay = 0,
        bool $isGroup = false
    ): array {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $payload = [
            'number' => $recipient,
            'mediatype' => $mediatype,
            'mimetype' => $mimetype,
            'caption' => $caption,
            'media' => $media,
            'fileName' => $fileName,
        ];

        if ($delay > 0) {
            $payload['delay'] = $delay;
        }

        return $this->service->post("/message/sendMedia/{$this->instanceName}", $payload);
    }

    /**
     * Send a contact message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendContact(string $phoneNumber, string $contactName, string $contactNumber, bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $contact = new Contact(
            $contactName,
            $contactNumber,
            $contactNumber
        );

        $contactMessage = new ContactMessage(
            $recipient,
            [$contact]
        );

        return $this->service->post("/message/sendContact/{$this->instanceName}", $contactMessage->toArray());
    }

    /**
     * Send a poll message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendPoll(
        string $phoneNumber,
        string $name,
        int $selectableCount,
        array $values,
        ?int $delay = null,
        bool $isGroup = false
    ): array {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $pollMessage = new PollMessage(
            $recipient,
            $name,
            $selectableCount,
            $values,
            $delay
        );

        return $this->service->post("/message/sendPoll/{$this->instanceName}", $pollMessage->toArray());
    }

    /**
     * Send a list message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendList(
        string $phoneNumber,
        string $title,
        string $description,
        string $buttonText,
        string $footerText,
        array $sections,
        ?int $delay = null,
        bool $isGroup = false
    ): array {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $listMessage = new ListMessage(
            $recipient,
            $title,
            $description,
            $buttonText,
            $footerText,
            $sections,
            $delay
        );

        return $this->service->post("/message/sendList/{$this->instanceName}", $listMessage->toArray());
    }

    /**
     * Send a button message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendButtons(
        string $phoneNumber,
        string $title,
        string $description,
        string $footer,
        array $buttons,
        ?int $delay = null,
        bool $isGroup = false
    ): array {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $buttonMessage = new ButtonMessage(
            $recipient,
            $title,
            $description,
            $footer,
            $buttons,
            $delay
        );

        return $this->service->post("/message/sendButtons/{$this->instanceName}", $buttonMessage->toArray());
    }

    /**
     * Send a reaction to a message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendReaction(array $key, string $reaction): array
    {
        $reactionMessage = new ReactionMessage(
            $key,
            $reaction
        );

        return $this->service->post("/message/sendReaction/{$this->instanceName}", $reactionMessage->toArray());
    }

    /**
     * Send a status message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendStatus(
        string $type,
        string $content,
        ?string $caption = null,
        ?string $backgroundColor = null,
        ?int $font = null,
        bool $allContacts = false,
        ?array $statusJidList = null
    ): array {
        $statusMessage = new StatusMessage(
            $type,
            $content,
            $caption,
            $backgroundColor,
            $font,
            $allContacts,
            $statusJidList
        );

        return $this->service->post("/message/sendStatus/{$this->instanceName}", $statusMessage->toArray());
    }

    /**
     * Send an audio message.
     *
     * @param string $audio URL or base64
     *
     * @throws EvolutionApiException
     */
    public function sendAudio(string $phoneNumber, string $audio, bool $isGroup = false, int $delay = 1200): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/sendWhatsAppAudio/{$this->instanceName}", [
            'number' => $recipient,
            'audio' => $audio,
            'delay' => $delay,
        ]);
    }

    /**
     * Send a sticker message.
     *
     * @param string $sticker URL or base64
     *
     * @throws EvolutionApiException
     */
    public function sendSticker(string $phoneNumber, string $sticker, bool $isGroup = false, int $delay = 1200): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/sendSticker/{$this->instanceName}", [
            'number' => $recipient,
            'sticker' => $sticker,
            'delay' => $delay,
        ]);
    }

    /**
     * Send a template message.
     *
     *
     * @throws EvolutionApiException
     */
    public function sendTemplate(
        string $phoneNumber,
        string $name,
        string $language,
        array $components,
        ?string $webhookUrl = null,
        bool $isGroup = false
    ): array {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $template = new TemplateMessage(
            $recipient,
            $name,
            $language,
            $components,
            $webhookUrl
        );

        return $this->service->post("/message/sendTemplate/{$this->instanceName}", $template->toArray());
    }
}
