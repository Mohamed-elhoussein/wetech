<?php

namespace App\Http\Controllers;

use App\Helpers\Loader;
use App\Helpers\SimpleCSV;
use App\Http\BulkActions\UserBulkAction;
use App\Http\Controllers\api\ProviderApiController;
use App\Http\Requests\UpdateProviderSkillsRequest;
use App\Http\Requests\UpdateSocialMediaLinksRequest;
use App\Models\Countries;
use App\Models\ProviderServices;
use App\Models\Skill;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProviderController extends Controller
{

    public function create()

    {
        $countries  =   Countries::get(['id', 'name', 'code', 'country_code']);

        return   view('provider.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $number_phone   =   $request->number_phone;
        $country_id     =   $request->country_id;
        $this->validate(
            $request,
            [
                'number_phone' => [
                    Rule::unique('users')->where(function ($query) use ($number_phone, $country_id) {
                        return $query->where('number_phone', $number_phone)
                            ->where('country_id', $country_id)
                            ->where('role', 'provider');
                    })
                ]
            ],
            ['number_phone.unique' => "The provider is already created, log in our app and check if it is registered"]
        );


        $this->validate($request, rules('provider.create_'));
        $fields       = $request->all();
        User::create([
            'number_phone'   =>       $fields['number_phone'],
            'username'       =>       $fields['username'] ?? null,
            'country_id'     =>       $fields['country_id'],
            'first_name'     =>       $fields['first_name'],
            'avatar'         =>       isset($fields['avatar']) ? upload_picture($fields['avatar'], '/images/avatars') : '/images/avatars/default.png',
            'second_name'    =>       $fields['second_name'],
            'last_name'      =>       $fields['last_name'],
            'friend_number'  =>       $fields['friend_number'],
            'role'           =>       'provider',
            'email'          =>       $fields['email'],
            'order'          =>       $fields['order'],
            'verified'       =>       true,
            'identity'       =>       upload_picture($fields['identity'], '/images/identity'),
        ]);
        return redirect()->route('user.providers')->with('created', 'provider was created successefly');
    }

    public function edit($id)
    {


        $countries  =   Countries::get(['id', 'name', 'code', 'country_code']);
        $provider       =       User::withTrashed()->where('id', $id)->with('country:id,name,code,country_code')->first();
        return view('provider.edit', compact('provider', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $fields     =   $request->all();
        $provider   =   User::where('id', $id)->first();

        $provider->username       =       $fields['username'];
        $provider->email          =       $fields['email'];
        $provider->number_phone   =       $fields['number_phone'];
        $provider->country_id     =       $fields['country_id'];
        $provider->avatar         =       isset($fields['avatar'])      ?       upload_picture($request->file('avatar'), '/images/avatars') : $provider->avatar;
        $provider->first_name     =       $fields['first_name'];
        $provider->second_name    =       $fields['second_name'];
        $provider->last_name      =       $fields['last_name'];
        $provider->friend_number  =       $fields['friend_number'];
        $provider->order  =       $fields['order'];
        $provider->identity       =       isset($fields['identity'])    ?       upload_picture($request->file('identity'), '/images/identity') : $provider->identity;

        $provider->save();
        return redirect()->route('user.providers')->with('updated', 'provider was updated successefly');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return  redirect()->route('user.providers')->with('deleted', 'provider was deleted successefly');
    }
    public function profile(UpdateSocialMediaLinksRequest $request, $id)
    {
        $provider  =  User::withTrashed()->with(['rates', 'commission:id,commission,provider_id,percentage', 'provider_skills', 'rate' => function ($query) {
            $query->selectRaw(' avg(stars) as rating');
        }])->withCount('rate')->findOrFail($id);

        $skills = Skill::all();

        if ($request->isMethod('POST')) {
            $data = $request->validated();

            $provider->update([
                'social_media_links' => $data['social_media_links']
            ]);

            return redirect()->route('provider.profile', compact('id'));
        }

        return view('provider.profile', compact('provider', 'skills'));
    }
    public function serviceDetails(Request $request, $id)
    {

        $service  = ProviderServices::where('id', $id)->with('provider', 'provider.provider_services')->first();

        $service->gallery =    collect(explode('||', $service->gallery))->map(function ($item) {
            return $item ? url($item) : default_image();
        })->values()->toArray();


        return view('providers.services.details', compact('service'));
    }

    public function updateTransactionUsd(Request $request, User $user)
    {
        DB::table('transactions')->where('user_id', $user->id)->update(['is_usd' => (bool) $request->get('is_usd')]);
        return redirect()->route('user.providers')->with('updated', 'provider was updated successefly');
    }

    public function ajax(int $id, Request $request)
    {
        $request->validate([
            'value' => [
                'required',
                'boolean'
            ],
            'type' => [
                'required',
                'string',
                'in:email_verification,phone_verification,identity_verification'
            ]
        ]);

        $provider  =  User::where('role', 'provider')->findOrFail($id);
        $value = $request->get('value');

        switch ($request->get('type')) {
            case 'email_verification':
                $provider->update([
                    'email_verified' => $value
                ]);
                break;
            case 'phone_verification':
                $provider->update([
                    'verified' => $value
                ]);
                break;
            case 'identity_verification':
                $provider->update([
                    'identity_verified' => $value
                ]);
                break;
        }

        return new JsonResponse(['success' => true]);
    }

    public function skills(UpdateProviderSkillsRequest $request, $id)
    {
        $provider  =  User::provider()->findOrFail($id);

        $skills = collect(array_filter($request->validated()['skills']))->map(function ($skill_id) use ($provider) {
            return [
                'skill_id' => $skill_id,
                'user_id' => $provider->id,
            ];
        })->toArray();

        $provider->provider_skills()->delete();
        $provider->provider_skills()->insert($skills);

        return redirect()->back()->with('update', 'تم تحديث المهارات بنجاح');
    }

    public function bulkAction(UserBulkAction $providerBulkAction)
    {
        User::provider()->bulkAction($providerBulkAction);
    }

    public function import(Request $request)
    {
        $request->validate(rules('providers.import'), [
            'file.mimetypes' => 'يجب أن يكون الملف من النوع: text/csv،application/csv'
        ]);

        $file = $request->file('file');

        $rows = SimpleCSV::import($file);

        $providers = collect($rows)->skip(1)->map(function ($row) {
            if (count($row) === 8) {
                return [
                    'username' => $row[0],
                    'country_id' => Loader::getCountryId($row[1]),
                    'number_phone' => $row[2],
                    'first_name' => $row[3],
                    'second_name' => $row[4],
                    'last_name' => $row[5],
                    'friend_number' => $row[6],
                    'email' => $row[7],
                    'role' => 'provider',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        })->filter(function ($provider) {
            return $provider !== null;
        })->toArray();

        User::insert($providers);

        return redirect()->route('user.providers')->with('created', 'تم رفع المزودين بنجاح');
    }
}
