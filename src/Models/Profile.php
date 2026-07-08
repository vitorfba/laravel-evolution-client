<?php

// src/Models/Profile.php

namespace Happones\LaravelEvolutionClient\Models;

class Profile
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new Profile instance.
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
