@extends('layouts.admin')
@section('title', 'Create Member')

@section('content')
    <div id="controller">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Create a Member
                        </h3>
                    </div>
                    <form action="{{ route('members.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Enter Name" required name="name">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" placeholder="Enter Phone Number" name="phone_number"
                                    class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <div class="form-check">
                                    <input type="radio" name="gender" class="form-check-input" value="L" required>
                                    <label class="form-check-label">Laki - Laki</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="gender" class="form-check-input" value="P" required>
                                    <label class="form-check-label">Perempuan</label>
                                </div>
                            </div>

                            <input type="hidden" name="status" value="1">
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
