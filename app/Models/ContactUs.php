<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use SoftDeletes;

    protected $table = 'contact_us';

    protected $fillable = [
        'type',
        'name',
        'company_name',
        'email',
        'mobile_no',
        'country',
        'city',
        'address',
        'message',
        'status',
    ];
}
