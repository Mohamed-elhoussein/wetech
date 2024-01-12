<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public static function mapChatMessage($ChatCollection, $user = NULL)
    {
        $pay_methods = PayMethodes::all();

        $ChatCollection->map(function ($item) use ($pay_methods, $user){
            if ($item->type == 'offer') {
                $offer  = Offer::where('id', (int)$item->message)->select('id', 'description', 'price', 'status', 'provider_id', 'provider_service_id')->withTrashed()->first();
                $offer->unit         =  $offer->provider->country->null ?? 'NAN';
                $offer->target       =  $offer->service->service_full->target;
                $offer->service_name =  $offer->service->service_full->name;
                $offer->pay_methods  =  $offer->service->service_full->target === 'online_services' ? [$pay_methods[0], $pay_methods[1]] : [$pay_methods[2]];
                unset($offer->service);
                $item->message  =  json_decode((string)collect($offer)->except('provider'));
            }

            $item->type == 'image'    ?   $item->message  =   url('') . $item->message                                          :   false;
            $item->type == 'file'     ?   $item->message  =   url('') . '/file/' . $item->message                               :   false;
            $item->type == 'audio'    ?   $item->message  =   url('') . '/file/' . $item->message                               :   false;
            $item->type == 'text'     ?   $item->message  =   $item->message                                                    :   false;
            $item->type == 'location' ?   $item->message  =   $item->message                                                    :   false;


            if($item->review != NULL && $user != NULL)
            {
                $user->id != $item->send_by ? $item->review->review  =  config_index(386) : false;
            }

            return $item;
        });

        return $ChatCollection;
    }

    public function scopeproviderChat($query)
    {
        $query->whereRaw('id in (select max(id) from chats group by   (provider_id) ,(user_id) order by created_at desc)')
        ->withTrashed()->with('user:id,username,avatar,email,role,created_at');
        return $query;
    }
    public function scopeuserChat($query)
    {
        $query->whereRaw('id in (select max(id) from chats group by (provider_id) ,(user_id) order by created_at desc)')
            ->with('provider:id,username,avatar,email,role,created_at')
            ->whereHas('provider', function ($query)  {
                return $query->Where('role', '!=', 'technical_advisor');
            });
        return $query;
    }
    public function scopeobserverChat($query)
    {
        $query->whereRaw('id in (select max(id) from chats group by (provider_id) order by created_at desc)');
        return $query;
    }

    public function review()
    {
        return $this->hasOne(MessageReport::class, 'message_id');
    }
}
