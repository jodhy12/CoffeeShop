@extends('layouts.admin')
@section('title', 'Category')

@section('content')
    <div id="controller">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">
                        Create new category
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
                        <th class="text-center">Description</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(category, value) in categories">
                        <td class="text-center">@{{ value + 1 }}</td>
                        <td>@{{ category.name }}</td>
                        <td>@{{ category.description }}</td>
                        <td class="row justify-content-center">
                            <a :href="actionUrl + '/' + category.id + '/edit'">
                                <button class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit "></i>
                                </button>
                            </a>

                            <form :action="actionUrl + '/' + category.id" method="POST">
                                @csrf
                                @method('delete')

                                <button onclick="return confirm('Are you sure delete this ?')" type="submit" title="Delete"
                                    class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
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
                    actionUrl: '{{ route('categories.index') }}',
                    categories: {!! json_encode($categories) !!}
                }
            },

            mounted() {
                $('#datatable').DataTable()
            }

        }).mount('#controller')
    </script>

@endsection
