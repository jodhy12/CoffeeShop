@extends('layouts.admin')
@section('title', 'Members')

@section('content')
    <div id="controller">
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
            @if (session()->has('message'))
                <div class="aler alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            <table class="table table-bordered table-striped">
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
                    <tr v-for="(member, value) in members.data">
                        <td class="text-center">@{{ members.from + value }}</td>
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
                                <button class="btn btn-warning btn-sm" title="Edit"><a
                                        :href="actionUrl + '/' + member.id + '/edit'"><i
                                            class="fas fa-edit "></i></a></button>
                                <form :action="actionUrl + '/' + member.id" method="POST">
                                    @csrf
                                    @method('delete')

                                    <button onclick="return confirm('Are you sure delete this ?')" type="submit"
                                        title="Delete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix" v-if="members.data > members.per_page">
            <ul class="pagination float-right">
                <li class="page-item">
                    <a class="page-link" :href="members.prev_page_url">&laquo;</a>
                </li>
                <li v-for="value in members.last_page" class="page-item">
                    <a class="page-link" :class="{ active: value == members.current_page }"
                        :href="urlPage + value">@{{ value }}</a>
                </li>
                <li class="page-item">
                    <a class="page-link" :href="members.next_page_url">&raquo;</a>
                </li>
            </ul>
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
                    urlPage: 'http://localhost:8000/members?page=',
                    members: {!! json_encode($members) !!}
                }
            },

            mounted() {}

        }).mount('#controller')
    </script>

@endsection
