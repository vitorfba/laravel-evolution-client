<?php

namespace Happones\LaravelEvolutionClient\Models;

class ProfileStatus extends Profile
{
    /**
     * Create a new ProfileStatus instance.
     */
    public function __construct(string $status)
    {
        parent::__construct(['status' => $status]);
    }
}
