<?php

// src/Models/PollMessage.php

namespace Vitorfba\LaravelEvolutionClient\Models;

class PollMessage
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new PollMessage instance.
     */
    public function __construct(
        string $number,
        string $name,
        int $selectableCount,
        array $values,
        ?int $delay = null,
        ?QuotedMessage $quoted = null
    ) {
        $this->attributes = [
            'number' => $number,
            'name' => $name,
            'selectableCount' => $selectableCount,
            'values' => $values,
        ];

        if ($delay !== null) {
            $this->attributes['delay'] = $delay;
        }

        if ($quoted !== null) {
            $this->attributes['quoted'] = $quoted->toArray();
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
