<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $fillable = [
        'mail_new_policy',
        'mail_cc_uw',
        'mail_from_uw',
        'mail_from_name',
    ];
}
