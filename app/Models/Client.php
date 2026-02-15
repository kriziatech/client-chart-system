<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Carbon\Carbon;

class Client extends Model
{
    use Auditable;

    protected $guarded = [];

    /**
     * Financial Control Room Relationships
     */
    public function vendorPayments()
    {
        return $this->hasMany(VendorPayment::class);
    }

    public function materialInwards()
    {
        return $this->hasMany(MaterialInward::class);
    }

    public function materialPayments()
    {
        return $this->hasMany(MaterialPayment::class);
    }

    public function financials()
    {
        return $this->hasOne(ProjectFinancial::class)->withDefault([
            'budget_locked_amount' => 0,
            'is_locked' => false,
            'expected_profit_margin' => 15.00
        ]);
    }

    /**
     * Financial Calculations
     */
    public function getTotalClientReceivedAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getTotalVendorPaidAttribute()
    {
        return $this->vendorPayments()->sum('amount');
    }

    public function getTotalMaterialCostAttribute()
    {
        return $this->materialInwards()->sum('total_amount');
    }

    public function getTotalMaterialPaidAttribute()
    {
        return $this->materialPayments()->sum('amount_paid');
    }

    public function getRealTimeProfitAttribute()
    {
        return $this->total_client_received - ($this->total_vendor_paid + $this->total_material_cost);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'start_date' => 'date',
        'delivery_date' => 'date', // Fixed cast name
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string)\Illuminate\Support\Str::uuid();
            }
            // Assign temporary file_number to pass database constraint
            if (empty($model->file_number)) {
                $model->file_number = 'TEMP-' . \Illuminate\Support\Str::random(10);
            }
        });

        static::created(function ($model) {
            // Update to P-{id} format if it was a temporary value
            if (str_starts_with($model->file_number, 'TEMP-')) {
                $model->file_number = 'P-' . str_pad($model->id, 4, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            }
        });
    }

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function siteInfo()
    {
        return $this->hasOne(SiteInfo::class);
    }

    public function permission()
    {
        return $this->hasOne(Permission::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProjectGallery::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class);
    }

    public function projectMaterials()
    {
        return $this->hasMany(ProjectMaterial::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function changeRequests()
    {
        return $this->hasMany(ChangeRequest::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    /**
     * Handover & Feedback
     */
    public function handover()
    {
        return $this->hasOne(Handover::class);
    }

    public function feedback()
    {
        return $this->hasOne(ProjectFeedback::class);
    }

    public function scopeOfWork()
    {
        return $this->hasOne(ScopeOfWork::class);
    }

    /**
     * Journey Stage Detection (8 Stages)
     * 1: New Client, 2: Site Visit, 3: Quotation, 4: Credit, 5: Work Assigned, 6: Timeline, 7: Work Completed, 8: Final Payment
     */
    public function getJourneyStageAttribute(): int
    {
        if ($this->handover && $this->handover->status === 'completed') {
            return 8; // Final Payment
        }
        if ($this->status === 'Completed') {
            return 7; // Work Completed
        }
        if ($this->delivery_date) {
            return 6; // Timeline
        }
        if ($this->tasks()->exists()) {
            return 5; // Work Assigned
        }
        if ($this->payments()->exists() || $this->paymentRequests()->exists()) {
            return 4; // Credit
        }
        if ($this->quotations()->exists()) {
            return 3; // Quotation
        }
        if ($this->siteInfo()->exists()) {
            return 2; // Site Visit
        }
        return 1; // New Client
    }

    public function getRiskAnalysisAttribute()
    {
        $overdueTasks = $this->tasks()->where('status', '!=', 'Completed')->where('deadline', '<', now())->count();
        $totalTasks = $this->tasks()->count();
        $completedTasks = $this->tasks()->where('status', 'Completed')->count();

        $riskScore = 0; // 0-100
        $reasons = [];

        // Check Overdue
        if ($overdueTasks > 0) {
            $riskScore += 40;
            $reasons[] = "$overdueTasks tasks are overdue.";
        }

        // Check Deadline Proximity
        if ($this->delivery_date && $this->delivery_date->isPast() && $this->status !== 'Completed') {
            $riskScore += 50;
            $reasons[] = "Project is past delivery date.";
        }
        elseif ($this->delivery_date && $this->delivery_date->diffInDays(now()) < 7 && $completedTasks < ($totalTasks * 0.8)) {
            $riskScore += 30;
            $reasons[] = "Delivery date is approaching with low completion rate.";
        }

        // Progress check
        $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
        if ($totalTasks > 5 && $progress < 20 && $this->created_at->diffInDays(now()) > 14) {
            $riskScore += 20;
            $reasons[] = "Slow progress over last 2 weeks.";
        }

        $level = 'Low';
        if ($riskScore >= 70)
            $level = 'High';
        elseif ($riskScore >= 40)
            $level = 'Medium';

        return [
            'score' => min($riskScore, 100),
            'level' => $level,
            'reasons' => $reasons,
            'projected_delay' => $this->calculateProjectedDelay($completedTasks, $totalTasks)
        ];
    }

    private function calculateProjectedDelay($completed, $total)
    {
        if ($completed == 0 || $total == 0)
            return 0;

        $daysElapsed = $this->created_at->diffInDays(now());
        if ($daysElapsed == 0)
            return 0;

        $velocity = $completed / $daysElapsed; // tasks per day
        $remaining = $total - $completed;

        $daysToFinish = $remaining / ($velocity ?: 1);

        if ($this->delivery_date) {
            $projectedFinish = now()->addDays($daysToFinish);
            if ($projectedFinish->gt($this->delivery_date)) {
                return $projectedFinish->diffInDays($this->delivery_date);
            }
        }
        return 0;
    }
}