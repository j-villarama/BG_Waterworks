<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;
    protected $table = 'loan_payment';

    protected $fillable = [
        'loan_info_id',
        'payment_no',
        'amount',
        'payment_date',
        'due_date',
        'paid_status',
        
    ];
}
