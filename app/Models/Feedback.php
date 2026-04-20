<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    public const STATUS_PENDING_FACULTY = 'pending_faculty';
    public const STATUS_PENDING_PRINCIPAL = 'pending_principal';
    public const STATUS_PENDING_ADMIN = 'pending_admin';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $table = 'feedbacks';

    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'title',
        'message',
        'feedback_week',
        'submitted_at',
        'workflow_status',
        'approved_by_faculty_at',
        'approved_by_principal_at',
        'approved_by_admin_at',
        'rejected_at',
        'rejected_by_user_id',
        'status_note',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'approved_by_faculty_at' => 'datetime',
            'approved_by_principal_at' => 'datetime',
            'approved_by_admin_at' => 'datetime',
            'rejected_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by_user_id');
    }
}
