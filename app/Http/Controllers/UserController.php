<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin:superadmin,admin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $users = User::where('role', 'employee')->get();
        } else {
            $users = User::all();
        }
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (auth()->user()->role === 'admin') {
            $this->validate($request, [
                'role' => ['required', 'in:employee'],
            ]);
        } else {
            $this->validate($request, [
                'role' => ['required', 'in:admin,employee'],
            ]);
        }
        User::create([
            'username' => strtolower($request->username),
            'name' => ucwords($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        session()->flash('message', 'Data has been saved');
        session()->flash('alert-class', 'alert-success');

        return redirect('users');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort(404, 'Page Not Found');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (Auth::user()->role == 'superadmin') {
            if ($user->role == 'superadmin') {
                abort(404, 'Page Not Found');
            }
            return view('admin.user.edit', compact('user'));
        }
        if ($user->role !== 'employee') {
            abort(404, 'Page Not Found');
        }
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $this->validate($request, [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'in:superadmin,admin,employee'],
        ]);

        if (auth()->user()->role === 'admin') {
            $this->validate($request, [
                'role' => ['required', 'in:employee'],
            ]);
        } else {
            $this->validate($request, [
                'role' => ['required', 'in:superadmin,admin,employee'],
            ]);
        }

        $user->update([
            'username' => strtolower($request->username),
            'name' => ucwords($request->name),
            'email' => $request->email,
            'status' => intval($request->status),
            'role' => $request->role,
        ]);

        session()->flash('message', 'Data has been updated');
        session()->flash('alert-class', 'alert-success');
        return redirect('users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (Auth::user()->role == 'superadmin') {
            if ($user->role == 'superadmin') {
                session()->flash('message', 'Cannot delete this role');
                session()->flash('alert-class', 'alert-danger');
                return redirect('users');
            }

            $user->delete();

            session()->flash('message', 'Data has been removed');
            session()->flash('alert-class', 'alert-success');
            return redirect('users');
        }
        if ($user->role === 'employee') {
            $user->delete();

            session()->flash('message', 'Data has been removed');
            session()->flash('alert-class', 'alert-success');

            return redirect('users');
        }

        session()->flash('message', 'Cannot delete this role');
        session()->flash('alert-class', 'alert-danger');
        return redirect('users');
    }
}
