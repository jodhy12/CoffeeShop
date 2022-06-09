@extends('layouts.admin')
@section('title', 'Category')

@section('content')
    <div id="controller">
        <div class="bg-table">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('categories.create') }}" class="btn btnNew">
                            Create new category
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div v-if="categories.data > 0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(category, value) in categories.data">
                                <td class="text-center">@{{ categories.from + value }}</td>
                                <td>@{{ category.name }}</td>
                                <td>@{{ category.description }}</td>
                                <td>
                                    <a class="btn btnNew btn-sm" title="Edit" :href="actionUrl + '/' + category.id"><i
                                            class="fas fa-edit"></i></a>
                                    <form :action="actionUrl + '/' + category.id" method="POST">
                                        @csrf
                                        @method('delete')

                                        <button type="submit" title="Delete" class="btn btnNew btn-sm"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="text-center">
                    Data Empty...
                </div>
            </div>

            <div class="card-footer clearfix" v-if="categories.data > categories.per_page">
                <ul class="pagination float-right">
                    <li class="page-item">
                        <a class="page-link" :href="categories.prev_page_url">&laquo;</a>
                    </li>
                    <li v-for="value in categories.last_page" class="page-item">
                        <a class="page-link" :class="{ active: value == categories.current_page }"
                            :href="urlPage + value">@{{ value }}</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" :href="categories.next_page_url">&raquo;</a>
                    </li>
                </ul>
            </div>

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
                    urlPage: 'http://localhost:8000/categories?page=',
                    categories: {!! json_encode($categories) !!}
                }
            },

            mounted() {
                console.log(this.categories)
            }

        }).mount('#controller')
    </script>

@endsection
