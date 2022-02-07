<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{

    protected $table = 'user_experiences';

    protected $fillable = ["user_id", "organization", "designation", "from_date", "is_left_job", "to_date", "is_still_active"];


    protected $casts = [
        "is_left_job" =>"boolean",
        "is_still_active" =>"boolean",
    ];
}
