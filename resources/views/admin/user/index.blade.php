@extends('layouts.admin')

@section('title', 'User')
@section('content')
    <div id="controller">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            Create new user
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
                            <th class="text-center">Username</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, value) in users">
                            <td class="text-center">@{{ value + 1 }}</td>
                            <td>@{{ user.username }}</td>
                            <td>@{{ user.name }}</td>
                            <td>@{{ user.email }}</td>
                            <td>
                                <div class="text-center rounded"
                                    :class="{ 'bg-primary': user.status, 'bg-danger': !user.status }">
                                    @{{ user.status ? 'Active' : 'Non Active' }}
                                </div>
                            </td>
                            <td class="text-center">@{{ user.role }}</td>
                            <td class="row justify-content-center">
                                @if (Auth::user()->role !== 'employee')
                                    <a :href="actionUrl + '/' + user.id + '/edit'" v-if="user.role != 'superadmin'">
                                        <button class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </a>
                                    <form :action="actionUrl + '/' + user.id" method="POST"
                                        v-if="user.role != 'superadmin'">
                                        @csrf
                                        @method('delete')

                                        <button onclick="return confirm('Are you sure delete this ?')" type="submit"
                                            title="Delete" class="btn btn-danger btn-sm"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
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
                    actionUrl: '{{ route('users.index') }}',
                    users: {!! json_encode($users) !!}
                }
            },

            mounted() {
                $('#datatable').DataTable()
            }

        }).mount('#controller')
    </script>
@endsection
