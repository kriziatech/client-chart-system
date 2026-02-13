<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use Carbon\Carbon;

class Client extends Model
{
    use Auditable;

    protected $guarded = [];

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

            if (empty($model->file_number)) {
                $datePart = now()->format('dmY'); // DDMMYYYY
                // User asked for IT-DDMMYY-001, so DDMMYY
                $datePartShort = now()->format('dmy');

                $prefix = "IT-{$datePartShort}-";

                $latestClient = static::where('file_number', 'like', "{$prefix}%")
                    ->orderBy('file_number', 'desc')
                    ->first();

                $sequence = 1;
                if ($latestClient) {
                    $parts = explode('-', $latestClient->file_number);
                    $lastSeq = (int)end($parts);
                    $sequence = $lastSeq + 1;
                }

                $model->file_number = $prefix . str_pad($sequence, 3, '0', STR_PAD_LEFT);
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
     * AI Risk Analysis
     */
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