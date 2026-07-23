<?php

// src/Models/ListMessage.php

namespace Vitorfba\LaravelEvolutionClient\Models;

class ListMessage
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new ListMessage instance.
     */
    public function __construct(
        string $number,
        string $title,
        string $description,
        string $buttonText,
        string $footerText,
        array $sections,
        ?int $delay = null,
        ?QuotedMessage $quoted = null
    ) {
        $sectionsArray = [];

        foreach ($sections as $section) {
            if ($section instanceof ListSection) {
                $sectionsArray[] = $section->toArray();
            } else {
                $sectionsArray[] = $section;
            }
        }

        $this->attributes = [
            'number' => $number,
            'title' => $title,
            'description' => $description,
            'buttonText' => $buttonText,
            'footerText' => $footerText,
            'values' => $sectionsArray,
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
