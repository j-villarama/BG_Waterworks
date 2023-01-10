<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInfo extends Model
{
    use HasFactory;
    protected $table = 'client_infos';

    protected $fillable = [
        'account_number',
        'status',
        'contact_number',
        'client_firstname',
        'client_lastname',
        'client_middlename',
        'client_birthday',
        'client_gender',
        'client_adress',
        'client_firstname',
        'client_profile_photo'
    ];
}

