<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAttachments extends Model
{
    use HasFactory;
    protected $table = 'customer_attachments';

    protected $fillable = [
        'files',
        'customer_id'
    ];
}
