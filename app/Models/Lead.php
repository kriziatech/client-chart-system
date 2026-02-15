<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use \App\Traits\Auditable;

    protected $fillable = [
        'offline_uuid',
        'name',
        'email',
        'phone',
        'whatsapp',
        'status',
        'source',
        'address',
        'location',
        'budget',
        'work_description',
        'assigned_to_id',
        'metadata',
        'notes',
        'last_follow_up_at',
        'next_follow_up_at',
        'score',
        'temperature',
    ];

    protected $appends = ['lead_number', 'days_inactive', 'needs_attention', 'formatted_budget'];

    protected $casts = [
        'metadata' => 'array',
        'last_follow_up_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
        'score' => 'integer',
        'budget' => 'decimal:2',
    ];

    /**
     * Status flow: New → Contacted → Visited → Quote Sent → Won / Lost
     */
    public const STATUSES = ['New', 'Contacted', 'Visited', 'Quote Sent', 'Won', 'Lost'];

    public const STATUS_COLORS = [
        'New' => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/30', 'text' => 'text-emerald-700 dark:text-emerald-400', 'dot' => 'bg-emerald-500'],
        'Contacted' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-400', 'dot' => 'bg-blue-500'],
        'Visited' => ['bg' => 'bg-amber-100 dark:bg-amber-900/30', 'text' => 'text-amber-700 dark:text-amber-400', 'dot' => 'bg-amber-500'],
        'Quote Sent' => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-700 dark:text-orange-400', 'dot' => 'bg-orange-500'],
        'Won' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-700 dark:text-green-400', 'dot' => 'bg-green-500'],
        'Lost' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-700 dark:text-red-400', 'dot' => 'bg-red-500'],
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class , 'assigned_to_id');
    }

    /**
     * Get days since last activity
     */
    public function getDaysInactiveAttribute(): int
    {
        $lastActivity = $this->last_follow_up_at ?? $this->updated_at ?? $this->created_at;
        return $lastActivity->diffInDays(now());
    }

    /**
     * Check if the lead needs attention
     */
    public function getNeedsAttentionAttribute(): bool
    {
        return $this->days_inactive >= 5 && !in_array($this->status, ['Won', 'Lost']);
    }

    /**
     * Format budget for display
     */
    public function getFormattedBudgetAttribute(): string
    {
        if (!$this->budget)
            return '—';
        if ($this->budget >= 10000000)
            return '₹' . number_format($this->budget / 10000000, 1) . ' Cr';
        if ($this->budget >= 100000)
            return '₹' . number_format($this->budget / 100000, 1) . 'L';
        if ($this->budget >= 1000)
            return '₹' . number_format($this->budget / 1000, 1) . 'K';
        return '₹' . number_format($this->budget);
    }
    /**
     * Get the next logical action for this lead
     */
    public function getNextStep(): string
    {
        return match ($this->status) {
                'New' => 'Contact the lead via Call or WhatsApp',
                'Contacted' => 'Schedule a site visit',
                'Visited' => 'Create and send a quotation',
                'Quote Sent' => 'Follow up for approval',
                'Won' => 'Convert to a Project Workspace',
                'Lost' => 'Evaluate reasons for loss',
                default => 'Wait for further action',
            };
    }

    /**
     * Get the sequential Lead Number (L-0001)
     */
    public function getLeadNumberAttribute(): string
    {
        return 'L-' . str_pad((string)$this->id, 4, '0', STR_PAD_LEFT);
    }
}