<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        $withDeleted = null;

        if (in_array(request('deleted'), User::FILTER) && request('deleted') === 'true') {
            $withDeleted = true;
        }

        $users = User::with('roles')
            ->when($withDeleted, function ($query) {
                $query->withTrashed();
            })
            ->paginate(20);

        return view('users.index', compact('users', 'withDeleted'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(CreateUserRequest $request)
    {
        $missingData = [];
        if(! $request->password) {
            $missingData['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
        }
        $data = array_merge($request->validated(), $missingData);

        $user = User::create($data);
        //$user->assignRole('user');

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(EditUserRequest $request, User $user)
    {
        $continue = true;
        $userId = $request->id;
        $email = $request->email;
        $userWithEmail = User::where('id', $userId)
        ->where('email', $email)
        ->first();

        if($userWithEmail) {
            $continue = true;
        } else {
            $userWithEmail = User::where('email', $email)->first();

            if($userWithEmail) {
                $continue = false;
            }
        }

        if(! $continue) {
            return back()->with('error', "this email has already been taken");
        }

        $user->update($request->validated());

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }

    public function askToChangePassword()
    {
        return view('auth.ask_to_change_password');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        currentUser()->update([
            'password' => bcrypt($request->new_password),
        ]);

        return redirect()->route('home')->with('status', 'Password changed successfully.');
    }
}
