@extends('layouts.admin')
@section('title', 'Create User')

@section('content')
    <div id="controller">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Create User
                        </h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Username" required name="username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Full Name" required name="name">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Email" required name="email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" required name="password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <select name="role" class="form-control">
                                    <option value="admin">Admin</option>
                                    <option value="employee">Employee</option>
                                </select>
                            </div>

                            <div class="col-4 float-right">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
