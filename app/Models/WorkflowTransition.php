<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class WorkflowTransition extends Model
{
    use HasUuids;
    public $timestamps = false;
    protected $guarded = [];
    protected function casts(): array { return ['context' => 'array', 'created_at' => 'immutable_datetime']; }
}
