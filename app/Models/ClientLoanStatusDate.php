<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLoanStatusDate extends Model
{
    use HasFactory;
    protected $table = 'client_loan_status_date';

    protected $fillable = [
        'client_info_id',
        'status',
        'actual_amount_on_status',
        'status_date',
        'remarks'
        
    ];
}
