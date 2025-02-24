<?php

namespace Botble\RealEstate\Models;

use Botble\Base\Supports\Avatar;
use Botble\Media\Models\MediaFile;
use Botble\RealEstate\Notifications\ConfirmEmailNotification;
use Botble\RealEstate\Notifications\ResetPasswordNotification;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use RealEstateHelper;
use RvMedia;

/**
 * @mixin \Eloquent
 */
class Account extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * @var string
     */
    protected $table = 're_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'avatar_id',
        'dob',
        'phone',
        'description',
        'gender',
        'company',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'dob',
        'package_start_date',
        'package_end_date',
    ];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new ConfirmEmailNotification());
    }

    /**
     * @return BelongsTo
     */
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class)->withDefault();
    }

    /**
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar->url) {
            return RvMedia::url($this->avatar->url);
        }

        try {
            return (new Avatar())->create($this->name)->toBase64();
        } catch (Exception $exception) {
            return RvMedia::getDefaultImage();
        }
    }

    /**
     * Always capitalize the first name when we retrieve it
     * @param string $value
     * @return string
     */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Always capitalize the last name when we retrieve it
     * @param string $value
     * @return string
     */
    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * @return string
     * @deprecated since v2.22
     */
    public function getFullName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * @return MorphMany
     */
    public function properties(): MorphMany
    {
        return $this->morphMany(Property::class, 'author');
    }

    /**
     * @return bool
     */
    public function canPost(): bool
    {
        return !RealEstateHelper::isEnabledCreditsSystem() || $this->credits > 0;
    }

    /**
     * @param int $value
     * @return int
     */
    public function getCreditsAttribute($value)
    {
        if (!RealEstateHelper::isEnabledCreditsSystem()) {
            return 0;
        }

        return $value ?: 0;
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    /**
     * @return BelongsToMany
     */
    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 're_account_packages', 'account_id', 'package_id');
    }
}
