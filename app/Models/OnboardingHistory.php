<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingHistory extends Model
{
    protected $connection = 'mysql';
    protected $table = 'onboarding_history';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
