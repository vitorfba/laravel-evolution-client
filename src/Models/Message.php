<?php

// src/Models/Message.php

namespace Vitorfba\LaravelEvolutionClient\Models;

class Message
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new Message instance.
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}
