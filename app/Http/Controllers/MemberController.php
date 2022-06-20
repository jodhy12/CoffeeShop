<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin:admin')
            ->only([
                'edit',
                'update',
                'destroy'
            ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member::all();
        return view('admin.member.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.member.create');
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
            'name' => ['required', 'max:64'],
            'gender' => ['required'],
            'phone_number' => ['required', 'min:10', 'max:13', 'unique:members'],
        ]);

        $data = $request->all();
        $data['name'] = ucwords($data['name']);
        Member::create($data);

        session()->flash('message', 'Data has been added');
        session()->flash('alert-class', 'alert-success');
        return redirect('members');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        abort(404, 'Page Not Found');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        return view('admin.member.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        $this->validate($request, [
            'name' => ['required', 'max:64'],
            'gender' => ['required'],
            'phone_number' => ['required', 'min:10', 'max:13', 'unique:members,phone_number,' . $member->id . ',id'],
            'status' => ['required', 'boolean'],
        ]);

        $member->update($request->all());

        session()->flash('message', 'Data has been update');
        session()->flash('alert-class', 'alert-success');
        return redirect('members');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        return 'Not Finish Yet';
    }
}
