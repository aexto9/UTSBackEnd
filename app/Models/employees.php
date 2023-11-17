<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employees extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'phone',
        'address',
        'email',
        'status',
        'hired_on',
    ];

    public function getEmpolyees(){

        $data = DB::table('employees')->get();
        return $data;
    }
}
