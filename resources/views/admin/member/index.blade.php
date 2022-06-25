@extends('layouts.admin')
@section('title', 'Members')

@section('content')
    <div id="controller">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('members.create') }}" class="btn btn-primary">
                            Create new member
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                {{ displayMessage() }}
                <table id="datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Gender</th>
                            <th class="text-center">Phone Number</th>
                            <th class="text-center">Status</th>
                            @if (Auth::user()->role == 'admin')
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(member, value) in members">
                            <td class="text-center">@{{ value + 1 }}</td>
                            <td>@{{ member.name }}</td>
                            <td>@{{ member.gender == 'L' ? 'Laki - Laki' : 'Perempuan' }}</td>
                            <td>@{{ member.phone_number }}</td>
                            <td>
                                <div class="text-center rounded"
                                    :class="{ 'bg-primary': member.status, 'bg-danger': !member.status }">
                                    @{{ member.status ? 'Active' : 'Non Active' }}
                                </div>
                            </td>
                            @if (Auth::user()->role == 'admin')
                                <td class="row justify-content-center">
                                    <a :href="actionUrl + '/' + member.id + '/edit'">
                                        <button class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit "></i>
                                        </button>
                                    </a>
                                    <form :action="actionUrl + '/' + member.id" method="POST">
                                        @csrf
                                        @method('delete')

                                        <button onclick="return confirm('Are you sure delete this ?')" type="submit"
                                            title="Delete" class="btn btn-danger btn-sm"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const {
            createApp
        } = Vue

        createApp({
            data() {
                return {
                    actionUrl: '{{ route('members.index') }}',
                    members: {!! json_encode($members) !!}
                }
            },

            mounted() {
                $('#datatable').DataTable()
            }

        }).mount('#controller')
    </script>

@endsection
