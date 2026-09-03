<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ContentRevision extends Model
{
    use HasUuids;
    public $timestamps = false;
    protected $guarded = [];
    protected function casts(): array { return ['snapshot' => 'array', 'created_at' => 'immutable_datetime']; }
}
