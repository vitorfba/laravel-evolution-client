<?php

namespace Happones\LaravelEvolutionClient\Models;

class ProfileName extends Profile
{
    /**
     * Create a new ProfileName instance.
     */
    public function __construct(string $name)
    {
        parent::__construct(['name' => $name]);
    }
}
