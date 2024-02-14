<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Filament\Models\Contracts\HasAvatar;

class Company extends Model implements HasName, HasAvatar
{
    use HasFactory;


    protected $fillable = [
        'name',
        'website',
    ];

    public function getFilamentName(): string
    {
        return "{$this->name}";
    }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function backlinks(): HasManyThrough
    {
        return $this->hasOneThrough(Backlink::class, Site::class,'id','id','site_id','company_id');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (strlen($this->website)> 9)
        {
            $urlData=parse_url($this->website);
            return "https://www.google.com/s2/favicons?domain=".$urlData['host']."&sz=256";
        }
        return false;

    }
}
