<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $table = 'task_user';

    const STATUS_PENDING = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;
}
