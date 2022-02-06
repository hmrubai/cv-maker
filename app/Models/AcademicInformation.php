<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicInformation extends Model {
    
    protected $table = 'academic_informations';

    protected $fillable = ["user_id", "exam_name", "institute", "cgpa", "year", "is_completed", "is_pursuing", "is_active"];

    public static $rules = [
        "user_id" => "required",
        "exam_name" => "required",
    ];

    protected $casts = [    
        "is_completed" =>"boolean",
        "is_pursuing" =>"boolean",
        "is_active" =>"boolean"   
    ];
}