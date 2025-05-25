<?php
// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'due_date', // Ajouté car requis par la migration
        'priority',
        'status',
        'assigned_to', // Cohérent avec la migration
        'created_by'   // Cohérent avec la migration
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'due_date' => 'datetime', // Ajouté
        'completed_at' => 'datetime', // Ajouté
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'priority' => 'string',
        'status' => 'string',
    ];

    // Enum pour priorités
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    // Enum pour statuts
    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FOR_VALIDATION = 'for_validation';

    // Relations
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Alias pour compatibilité avec l'ancien code
    public function user(): BelongsTo
    {
        return $this->assignedUser();
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('end_date', today());
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',      // Jaune pour "En attente"
            'in_progress' => 'blue',    // Bleu pour "En cours"
            'completed' => 'green',     // Vert pour "Terminée"
            'for_validation' => 'purple', // Violet pour "En validation"
            default => 'gray',
        };
    }
}