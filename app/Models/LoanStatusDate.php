<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanStatusDate extends Model
{
    use HasFactory;
    protected $table = 'loan_status_date';

    protected $fillable = [
        'loan_info_id',
        // 'loan_status_id',
        'status',
        'actual_amount_on_status',
        'status_date',
        'remarks'
        
    ];
}
