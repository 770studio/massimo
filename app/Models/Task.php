<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Task extends Model
{


    protected $guarded = [];
    protected $casts = [
        'task_data' => 'json',
        'execution_data' => 'json'
    ];


    public function assigned_to(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(Company::class, Process::class, 'id', 'id', 'process_id', 'company_id');
    }


}
