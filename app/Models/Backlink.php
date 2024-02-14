<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backlink extends Model
{
    use HasFactory;
    protected $fillable = ['site_id', 'user_id','link_url','linked_url','domain_rank','last_checked_at','contact_email','contact_name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function company()
    {
        //return $this->site()->company;
        return $this->hasOneThrough(Company::class, Site::class,'id','id','site_id','company_id');
    }
}
