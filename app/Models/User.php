<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \stdClass;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Filterable, HasBulkAction, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'first_name',
        'second_name',
        'last_name',
        'friend_number',
        'identity',
        'avatar',
        'number_phone',
        'about',
        'email',
        'balance_online',
        'balance',
        'country_id',
        'role',
        'active',
        'verified',
        'password',
        'is_blocked',
        'device_token',
        'permissions',
        'chat_reviews_permissions',
        'social_media_links',
        'number_profile_viewers',
        'identity_verified',
        'email_verified',
        'last_login',
        'order'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
        'subscribe',
    ];

    protected $casts = [
        'permissions' => 'array',
        'chat_reviews_permissions' => 'array',
        'social_media_links' => 'array',
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime:Y-m-d H:i:s',
        'updated_at'        => 'datetime:Y-m-d H:i:s',
        'last_login'        => 'datetime:Y-m-d H:i:s',
        'email_verified' => 'boolean',
        'identity_verified' => 'boolean',
        'last_login' => 'datetime'
    ];

    // protected $appends = ['subscribe_finished_at', 'remain_days', 'total_days'];
    // protected $appends = ['subscribe_finished_at'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->second_name . ' ' . $this->last_name;
    }

    public function permissions(){

        return $this->hasMany(monitorPermission::class, 'monitor_id');

    }


    public function getSubscribeFinishedAtAttribute()
    {
        return optional($this->subscribe()->first())->die_at ?? '';
    }

    public function getRemainDaysAttribute()
    {
        return max((strtotime(optional($this->subscribe()->first())->die_at) - strtotime(now())),  0) ?? 0;
    }

    public function getTotalDaysAttribute()
    {
        return optional($this->subscribe()->first())->total_days ?? '';
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->isForceDeleting()) {
                $user->transaction()->delete();
                $user->rate_user()->delete();
                $user->rate()->delete();
                $user->orders()->delete();
                $user->userOrders()->delete();
                $user->offers()->delete();
                $user->application_rate()->delete();
                $user->notifications()->delete();
                $user->chats()->delete();
                $user->provider_chats()->delete();
                $user->report()->delete();
                $user->commission()->delete();
            }
            $user->services()->delete();
        });
    }


    public function getSubscribeAttribute()
    {
        return $this->subscribe->die_at;
    }


    public function provider_services()
    {
        return $this->hasMany(ProviderServices::class, 'provider_id');
    }
    public function services()
    {
        return $this->hasMany(ProviderServices::class, 'provider_id')->orderBy('status', 'asc')->with('rating:stars');
    }
    public function services_accepted()
    {
        return $this->hasMany(ProviderServices::class, 'provider_id')->where('status', 'ACCEPTED')->with('rating:stars');
    }
    public function services_accepted_online()
    {
        return $this->hasMany(ProviderServices::class, 'provider_id')->where('status', 'ACCEPTED')->where('service_id', 6)->with('rating:stars');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'provider_id');
    }
    public function orders_completed()
    {
        return $this->hasMany(Order::class, 'provider_id')->where('status', 'COMPLETED');
    }
    public function orders_pending()
    {
        return $this->hasMany(Order::class, 'provider_id')->where('status', 'PENDING');
    }
    public function orders_canceled()
    {
        return $this->hasMany(Order::class, 'provider_id')->where('status', 'CANCELED');
    }
    public function orders_completed_user()
    {
        return $this->hasMany(Order::class, 'user_id')->where('status', 'COMPLETED');
    }
    public function orders_pending_user()
    {
        return $this->hasMany(Order::class, 'user_id')->where('status', 'PENDING');
    }
    public function orders_canceled_user()
    {
        return $this->hasMany(Order::class, 'user_id')->where('status', 'CANCELED');
    }
    public function report()
    {
        return $this->hasMany(Reports::class);
    }
    public function userOrders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    public function rate()
    {
        return $this->hasManyThrough(Rating::class, Order::class, 'provider_id');
    }
    public function rate_user()
    {
        return $this->hasManyThrough(Rating::class, Order::class, 'user_id');
    }
    public function rates()
    {
        return $this->hasManyThrough(Rating::class, Order::class, 'provider_id')->with('user:id,avatar,username');
    }

    // public function service(){
    //     return $this->belongsToMany(service::class,'provider_services','provider_id','service_id');
    // }
    public function country()
    {
        return $this->belongsTo(Countries::class, 'country_id');
    }
    public function offers()
    {
        return $this->hasMany(Offer::class, 'provider_id');
    }
    public function application_rate()
    {
        return $this->HasOne(AppRates::class);
    }
    public function notificationsNotSeenCount()
    {
        return $this->HasMany(Notification::class)->where('seen', 0);
    }
    public function notifications()
    {
        return $this->HasMany(Notification::class);
    }
    public function chats()
    {
        return $this->HasMany(Chat::class);
    }
    public function provider_chats()
    {
        return $this->HasMany(Chat::class, 'provider_id');
    }
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
    public function commission()
    {
        return $this->hasOne(ProviderCommission::class, 'provider_id');
    }
    public function subscribe()
    {
        return $this->hasOne(Subscribe::class)->where('is_paid', 1)->latest();
    }

    public function payments()
    {
        return $this->hasMany(ProductPayment::class);
    }

    public function scopeAdmin(Builder $query)
    {
        return $query->whereRole('admin');
    }

    public function scopeUser(Builder $query)
    {
        return $query->whereRole('user');
    }

    public function scopeChatReview(Builder $query)
    {
        return $query->whereRole('chat_review');
    }

    public function scopeProvider(Builder $query)
    {
        return $query->whereRole('provider');
    }

    public function provider_skills()
    {
        return $this->hasMany(ProviderSkill::class);
    }

    public function isProvider()
    {
        return $this->role === 'provider';
    }

    public function identities()
    {
        return $this->hasMany(Identity::class);
    }

    public function hasPendingIdentity()
    {
        return $this->identities()->where('status', 'pending')->count() > 0;
    }

    public function getUserStatusAttribute()
    {
        if ($this->is_blocked) {
            return 'تم حظره';
        }
        return 'يعمل';
    }

    /**
     * Define the relation between user and cart.
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function newInstance($attributes = [], $exists = false): self
    {
        $model = parent::newInstance($attributes, $exists);
        $model->setAppends($this->appends);

        return $model;
    }

    public static function withoutAppends(): Builder
    {
        $model = (new static);
        $model->setAppends([]);

        return $model->newQuery();
    }
}
