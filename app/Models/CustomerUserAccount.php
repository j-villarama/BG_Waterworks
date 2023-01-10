<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerUserAccount extends Model
{
    use HasFactory;
    protected $table = 'customer_user_account';

    protected $fillable = [
        'customer_id',
        'user_id',
        'username'

        
    ];
}
