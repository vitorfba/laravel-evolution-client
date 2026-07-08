<?php

// src/Models/LocationMessage.php

namespace Happones\LaravelEvolutionClient\Models;

class LocationMessage
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new LocationMessage instance.
     */
    public function __construct(
        string $number,
        string $name,
        string $address,
        float $latitude,
        float $longitude,
        ?int $delay = null,
        ?QuotedMessage $quoted = null
    ) {
        $this->attributes = [
            'number' => $number,
            'name' => $name,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
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
