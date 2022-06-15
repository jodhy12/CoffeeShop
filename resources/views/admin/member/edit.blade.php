@extends('layouts.admin')
@section('title', 'Edit Member')

@section('content')
    <div id="controller">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Edit Member
                        </h3>
                    </div>
                    <form action="{{ route('members.update', $member->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input value="{{ $member->name }}" type="text" class="form-control"
                                    placeholder="Enter Name" required name="name">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input value="{{ $member->phone_number }}" type="text" placeholder="Enter Phone Number"
                                    name="phone_number" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Gender</label>
                                <div class="form-check">
                                    <input {{ $member->gender == 'L' ? 'checked' : '' }} type="radio" name="gender"
                                        class="form-check-input" value="L" required>
                                    <label class="form-check-label">Laki - Laki</label>
                                </div>
                                <div class="form-check">
                                    <input {{ $member->gender == 'P' ? 'checked' : '' }} type="radio" name="gender"
                                        class="form-check-input" value="P" required>
                                    <label class="form-check-label">Perempuan</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="1" {{ $member->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $member->status == 0 ? 'selected' : '' }}>Non Active</option>
                                </select>
                            </div>

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
