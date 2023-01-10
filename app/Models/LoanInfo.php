<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanInfo extends Model
{
    use HasFactory;
    protected $table = 'loan_info';

    protected $fillable = [
        'customer_id',
        'amount_approved',
        'contact_no_months',
        'interest_rate',
        'customer_id',
        'current_status'
        
    ];
}
