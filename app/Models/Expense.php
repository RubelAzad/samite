<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'title', 'category', 'amount', 'date',
        'description', 'attachment', 'status', 'created_by', 'company_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function categories(): array
    {
        return [
            'meeting' => 'Meeting',
            'travel' => 'Travel / Visit',
            'office' => 'Office Cost',
            'communication' => 'Communication',
            'miscellaneous' => 'Miscellaneous',
        ];
    }
}
