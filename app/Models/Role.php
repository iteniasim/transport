<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    protected function name(): Attribute
    {
        return Attribute::get(fn (mixed $value) => Str::upper($value));
    }
}
