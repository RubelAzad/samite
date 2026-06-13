<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'amount', 'date',
        'description', 'attachment', 'status', 'created_by',
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
            'meeting'       => 'Meeting',
            'travel'        => 'Travel / Visit',
            'office'        => 'Office Cost',
            'communication' => 'Communication',
            'miscellaneous' => 'Miscellaneous',
        ];
    }
}
