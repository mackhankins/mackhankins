<?php

namespace App\Models;

use Database\Factories\WorkExperienceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    /** @use HasFactory<WorkExperienceFactory> */
    use HasFactory;

    protected $fillable = [
        'company',
        'title',
        'description',
        'company_url',
        'start_date',
        'end_date',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function isCurrent(): bool
    {
        return $this->end_date === null;
    }
}
