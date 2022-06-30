@extends('layouts.signForm')

@section('title', 'Login')

@section('content')
    <div class="login-box">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Login</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
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
                        <input type="password" class="form-control" placeholder="Password" required name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>

                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        <h5>For Admin</h5>
                        <p>username: admin</p>
                        <p>password: admin1234</p>
                    </div>
                    <div class="col-md-6">
                        <h5>For Employee</h5>
                        <p>username: employeesatu</p>
                        <p>password: test1234</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
