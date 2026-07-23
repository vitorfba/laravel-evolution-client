<?php

namespace Vitorfba\LaravelEvolutionClient\Webhook;

use Illuminate\Support\Arr;

/**
 * A parsed, typed representation of a single Evolution API webhook payload.
 *
 * Evolution delivers events with slightly different casing depending on the
 * server build (e.g. "messages.upsert" or "MESSAGES_UPSERT"); the event name is
 * normalised here to the lowercase dotted form ("messages.upsert").
 */
class WebhookEvent
{
    // Common Evolution event names (normalised, lowercase dotted).
    public const APPLICATION_STARTUP = 'application.startup';

    public const QRCODE_UPDATED = 'qrcode.updated';

    public const CONNECTION_UPDATE = 'connection.update';

    public const MESSAGES_UPSERT = 'messages.upsert';

    public const MESSAGES_UPDATE = 'messages.update';

    public const MESSAGES_DELETE = 'messages.delete';

    public const SEND_MESSAGE = 'send.message';

    public const CONTACTS_UPSERT = 'contacts.upsert';

    public const CONTACTS_UPDATE = 'contacts.update';

    public const PRESENCE_UPDATE = 'presence.update';

    public const CHATS_UPSERT = 'chats.upsert';

    public const CHATS_UPDATE = 'chats.update';

    public const CHATS_DELETE = 'chats.delete';

    public const GROUPS_UPSERT = 'groups.upsert';

    public const GROUP_PARTICIPANTS_UPDATE = 'group-participants.update';

    public const CALL = 'call';

    /**
     * @param array<string, mixed> $data the event payload (the "data" object)
     * @param array<string, mixed> $raw the full, unmodified request payload
     */
    public function __construct(
        public readonly string $event,
        public readonly ?string $instance,
        public readonly array $data,
        public readonly array $raw = [],
        public readonly ?string $sender = null,
        public readonly ?string $serverUrl = null,
        public readonly ?string $dateTime = null,
        public readonly ?string $destination = null,
    ) {}

    /**
     * Build a WebhookEvent from a raw decoded payload.
     *
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        $event = (string) ($payload['event'] ?? $payload['type'] ?? 'unknown');

        return new self(
            event: self::normalizeEventName($event),
            instance: isset($payload['instance']) ? (string) $payload['instance'] : null,
            data: (array) ($payload['data'] ?? []),
            raw: $payload,
            sender: isset($payload['sender']) ? (string) $payload['sender'] : null,
            serverUrl: isset($payload['server_url']) ? (string) $payload['server_url'] : null,
            dateTime: isset($payload['date_time']) ? (string) $payload['date_time'] : null,
            destination: isset($payload['destination']) ? (string) $payload['destination'] : null,
        );
    }

    /**
     * Normalise an event name to the lowercase dotted form.
     */
    public static function normalizeEventName(string $event): string
    {
        return strtolower(str_replace('_', '.', trim($event)));
    }

    /**
     * The normalised event name.
     */
    public function name(): string
    {
        return $this->event;
    }

    /**
     * Whether this is the given event (name is normalised before comparison).
     */
    public function is(string $event): bool
    {
        return $this->event === self::normalizeEventName($event);
    }

    /**
     * Read a value from the event data using "dot" notation.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->data, $key, $default);
    }

    public function isMessageUpsert(): bool
    {
        return $this->is(self::MESSAGES_UPSERT);
    }

    public function isConnectionUpdate(): bool
    {
        return $this->is(self::CONNECTION_UPDATE);
    }

    public function isQrCodeUpdated(): bool
    {
        return $this->is(self::QRCODE_UPDATED);
    }

    /**
     * The full payload as received.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->raw;
    }
}
