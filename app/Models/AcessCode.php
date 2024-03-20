<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcessCode extends Model
{
    use HasFactory;
    protected $table = 'access_code';

    protected $primaryKey = 'id';
    
    protected $fillable = 
    [
        "expires_at",
        "id_user",
        "name",
        "token",
        "pin"
    ];
}
