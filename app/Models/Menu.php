<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Menu extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'slug',
        'workbook',
        'view',
        'group',
    ];
}
