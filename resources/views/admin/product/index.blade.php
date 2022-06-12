@extends('layouts.admin')
@section('title', 'Product')
@section('content')
    <div id="controller">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        Create new product
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
                        <th class="text-center">Description</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(product, value) in products.data">
                        <td class="text-center align-middle">@{{ products.from + value }}</td>
                        <td class="align-middle">@{{ product.name }}</td>
                        <td class="align-middle">@{{ product.description }}</td>
                        <td class="text-center align-middle">@{{ product.category.name }}</td>
                        <td class="row justify-content-center">
                            <div class="card" style="width: 7rem">
                                <img class="card-img-top" :src="'/' + product.image_path">
                            </div>
                        </td>
                        <td class="text-center align-middle">@{{ product.qty }}</td>
                        <td class="text-center align-middle">Rp. @{{ numberFormat(product.price) }}</td>
                        <td class="row justify-content-around position-relative" style="bottom: 45px">
                            <button class="btn btn-warning btn-sm" title="Edit"><a
                                    :href="actionUrl + '/' + product.id + '/edit'"><i
                                        class="fas fa-edit "></i></a></button>
                            <form :action="actionUrl + '/' + product.id" method="POST">
                                @csrf
                                @method('delete')

                                <button type="submit" title="Delete" class="btn btn-danger btn-sm"><i
                                        class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix" v-if="products.data > products.per_page">
            <ul class="pagination float-right">
                <li class="page-item">
                    <a class="page-link" :href="products.prev_page_url">&laquo;</a>
                </li>
                <li v-for="value in products.last_page" class="page-item">
                    <a class="page-link" :class="{ active: value == products.current_page }"
                        :href="urlPage + value">@{{ value }}</a>
                </li>
                <li class="page-item">
                    <a class="page-link" :href="products.next_page_url">&raquo;</a>
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
                    actionUrl: '{{ route('products.index') }}',
                    urlPage: 'http://localhost:8000/products?page=',
                    products: {!! json_encode($products) !!}
                }
            },

            mounted() {},

            methods: {
                numberFormat(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },
            }

        }).mount('#controller')
    </script>

@endsection
