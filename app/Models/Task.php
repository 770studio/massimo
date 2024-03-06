<?php

namespace App\Models;

use App\Helpers\FormFields\FormFieldBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;

class Task extends Model
{


    protected $guarded = [];
    protected $casts = [
        'task_data' => 'json',
        'execution_data' => 'json'
    ];


    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(Company::class, Process::class, 'id', 'id', 'process_id', 'company_id');
    }

    public function buildForm(): Collection
    {

        return $this->process->configuration
            ->mapInto(FormFieldBuilder::class)
            ->map(fn(FormFieldBuilder $field) => $field->build($this->execution_data));
    }

}
