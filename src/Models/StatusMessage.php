<?php

// src/Models/StatusMessage.php

namespace Happones\LaravelEvolutionClient\Models;

class StatusMessage
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new StatusMessage instance.
     */
    public function __construct(
        string $type,
        string $content,
        ?string $caption = null,
        ?string $backgroundColor = null,
        ?int $font = null,
        bool $allContacts = false,
        ?array $statusJidList = null
    ) {
        $this->attributes = [
            'type' => $type,
            'content' => $content,
            'allContacts' => $allContacts,
        ];

        if ($caption !== null) {
            $this->attributes['caption'] = $caption;
        }

        if ($backgroundColor !== null) {
            $this->attributes['backgroundColor'] = $backgroundColor;
        }

        if ($font !== null) {
            $this->attributes['font'] = $font;
        }

        if ($statusJidList !== null) {
            $this->attributes['statusJidList'] = $statusJidList;
        }
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}
