<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetCodePasswordCaisse extends Model
{
    protected $fillable = ['code', 'email'];
}
