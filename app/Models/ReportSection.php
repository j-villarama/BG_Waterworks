<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSection extends Model
{
    use HasFactory;
    protected $table = 'report_section';

    protected $fillable = [
        'report_by',
        'report_description'
        
    ];
}
