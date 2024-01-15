<?php

namespace App\Http\Controllers;

use App\Exports\ProvidersExport;
use App\Exports\AdminsExport;
use App\Exports\ChatReviewsExport;
use App\Exports\UsersExport;
use App\Http\BulkActions\UserBulkAction;
use App\Http\Filters\ProviderFilter;
use App\Http\Requests\UpdateUsersRequest;
use App\Models\Countries;
use App\Models\User;
use App\Services\CsvExporterService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    private $exporterService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function admins(Request $request)
    {
        // $user=User::all();
        $users =   User::where([
            ['role', 'admin'],
            [function ($query) use ($request) {
                if ($request->key_search) {
                    $query->where('number_phone', 'LIKE', $request->key_search . '%')->orWhere('username', 'LIKE', $request->key_search . '%');
                }
            }]

        ])->paginate($request->get('limit', 15))->withQueryString();
        return view('user.index', compact('users'));
    }

    public function exportAdmins()
    {
        return Excel::download(new AdminsExport, 'users.xlsx');
    }

    public function providers(Request $request, ProviderFilter $filter)
    {

        $users = User::withTrashed()->filter($filter)->provider()->latest('last_name', 'desc')->with('country:id,name,country_code', 'commission')->paginate($request->get('limit', 15))->withQueryString();
        return view('user.providers', compact('users'));
    }

    public function exportProviders()
    {
        return Excel::download(new ProvidersExport, 'providers.xlsx');
    }

    public function users(Request $request)
    {


        $users =   User::withTrashed()->where([
            ['role', 'user'],
            [function ($query) use ($request) {
                if ($request->key_search) {
                    $query->where('number_phone', 'LIKE', $request->key_search . '%')->orWhere('username', 'LIKE', $request->key_search . '%');
                }
            }]

        ])->with('country:id,name,country_code')->paginate($request->get('limit', 15))->withQueryString();

        return view('user.users', compact('users'));
    }

    public function editUser(User $user)
    {

        $countries = Countries::get(['id', 'name', 'code', 'country_code']);
        return view('users.edit', compact('user', 'countries'));
    }

    public function updateUser(User $user, UpdateUsersRequest $request)
    {
        $user->update($request->getUpdatedFields());
        return redirect()->route('user.users')->with('updated', 'تم تحديث المستخذم بنجاح');
    }

    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function chat_reviews(Request $request)
    {
        $users =   User::where([
            ['role', 'chat_review'],
            [function ($query) use ($request) {
                if ($request->key_search) {
                    $query->where('number_phone', 'LIKE', $request->key_search . '%')->OrWhere('username', 'LIKE', $request->key_search . '%');;
                }
            }]

        ])->with('country:id,name,country_code')->paginate($request->get('limit', 15))->withQueryString();
        return view('user.chat_review', compact('users'));
    }

    public function exportChatReviews()
    {
        return Excel::download(new ChatReviewsExport, 'chat-reviews.xlsx');
    }

    public function store(Request $request)
    {
        $this->validator($request->all());
        $fields     =   $request->all();

        User::create([
            'email' => $fields['email'],
            'username' => $fields['username'],
            'number_phone' => $fields['number_phone'],
            'password' => Hash::make($fields['password']),
            'permissions' => array_keys($request->permission ?? []),
            'role'      => 'admin'
        ]);

        return  redirect()->route('user.admins')->with(['created' => 'user was created']);
    }


    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('user.edit', compact('user'));
    }


    public function profile()
    {
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->update_validator($request->all(), $id);
        $user = User::find($id);

        $user->update([
            'username'  =>  $request->username,
            'email'     =>  $request->email,
            'number_phone'     =>  $request->number_phone,
            'password'  =>  Hash::make($request->password),
            'permissions' => $request->permission ? collect($request->permission)->keys()->toArray() : []
        ]);

        return  redirect()->route('user.admins')->with('updated', 'user was updated');
    }


    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return  redirect()->route('user.users')->with('deleted', 'user was deleted');
    }
    public function deleteAdmin($id)
    {
        $user = User::find($id);

        $user->delete();
        return  redirect()->route('user.admins')->with('deleted', 'user was deleted');
    }



    public function block($id)
    {
        $user  =   User::where('id', $id)->first();

        $user->is_blocked    = $user->is_blocked   ?   0   :   1;

        $user->save();

        return  redirect()->back();
    }
    public function active($id)
    {
        User::where('id', $id)->update([
            'is_blocked' => 0
        ]);
        return  redirect()->back();
    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function update_validator(array $data, $id)
    {
        return Validator::make($data, [
            'name' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'username' => ['string', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => ['string', 'min:8', 'confirmed'],
            'permission' => ['nullable', 'array'],
        ]);
    }

    public function bulkAction(UserBulkAction $userBulkAction)
    {
        User::user()->bulkAction($userBulkAction);
    }

    public function chatReviewBulkAction(UserBulkAction $userBulkAction)
    {
        User::chatReview()->bulkAction($userBulkAction);
    }
}
