<?php

namespace App\Http\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('panel.users.index', compact('users'));
    }

    public function create()
    {
        return view('panel.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        if ($request->role == 'admin'){
            $request->validate(['password' => 'required']);
        }

        User::create([
            'name' => $request->name,
            'family' => $request->family,
            'national_code' => $request->national_code,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => $request->password ? bcrypt($request->password) : null,
        ]);

        alert()->success('کاربر مورد نظر با موفقیت ایجاد شد','ایجاد کاربر');
        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        return view('panel.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if ($request->role == 'admin'){
            $request->validate(['password' => 'required']);
        }

        $user->update([
            'name' => $request->name,
            'family' => $request->family,
            'national_code' => $request->national_code,
            'phone' => $request->phone,
            'role' => $request->role ?? $user->role,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        if (auth()->id() == $user->id){
            alert()->success('پروفایل شما با موفقیت ویرایش شد','ویرایش پروفایل');
            return redirect()->back();
        }else{
            alert()->success('کاربر مورد نظر با موفقیت ویرایش شد','ویرایش کاربر');
            return redirect()->route('users.index');
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }
}
