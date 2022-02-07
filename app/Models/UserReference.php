<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReference extends Model
{
    protected $table = 'user_references';
    protected $fillable = ['user_id','name','email','mobile','designation','organization'];
}
