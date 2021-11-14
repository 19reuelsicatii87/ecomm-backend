<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Exportable;

class Lead extends Model
{
    protected $guarded = [];
    protected $perPage = 30;
}
