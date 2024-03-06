<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Sanctum\HasApiTokens;
use Wallo\FilamentCompanies\Company as FilamentCompaniesCompany;
use Wallo\FilamentCompanies\Events\CompanyCreated;
use Wallo\FilamentCompanies\Events\CompanyDeleted;
use Wallo\FilamentCompanies\Events\CompanyUpdated;

class Company extends FilamentCompaniesCompany implements HasAvatar
{
    use HasFactory, HasApiTokens;

    public function getFilamentAvatarUrl(): string
    {
        return $this->owner->profile_photo_url;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'personal_company' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_company',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => CompanyCreated::class,
        'updated' => CompanyUpdated::class,
        'deleted' => CompanyDeleted::class,
    ];


    public function getFilamentName(): string
    {
        return "{$this->name}";
    }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function processes(): HasMany
    {
        return $this->hasMany(Process::class);
    }

    public function backlinks(): HasManyThrough
    {
        return $this->hasOneThrough(Backlink::class, Site::class,'id','id','site_id','company_id');
    }


}
