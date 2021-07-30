<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LogisticPortLogistic extends Pivot
{
    use HasFactory;
    protected $table = 'logistic_port_logistic';
}
