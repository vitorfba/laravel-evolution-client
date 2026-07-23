<?php

// src/Models/ListRow.php

namespace Vitorfba\LaravelEvolutionClient\Models;

class ListRow
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $rowId;

    /**
     * Create a new ListRow instance.
     */
    public function __construct(string $title, string $description, string $rowId)
    {
        $this->title = $title;
        $this->description = $description;
        $this->rowId = $rowId;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'rowId' => $this->rowId,
        ];
    }
}
