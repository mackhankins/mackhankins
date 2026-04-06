<?php

namespace App\Models;

use Database\Factories\CertificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    /** @use HasFactory<CertificationFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'issuer',
        'credential_url',
        'icon',
        'earned_at',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'earned_at' => 'date',
        ];
    }
}
