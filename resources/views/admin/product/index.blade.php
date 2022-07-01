@extends('layouts.admin')
@section('title', 'Product')
@section('content')
    <div id="controller">
        <div class="card">
            @if (Auth::user()->role != 'employee')
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                Create new product
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-body">
                {{ displayMessage() }}
                <table id="datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Image</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Price</th>

                            @if (Auth::user()->role != 'employee')
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, value) in products">
                            <td class="text-center align-middle">@{{ value + 1 }}</td>
                            <td class="align-middle">@{{ product.name }}</td>
                            <td class="align-middle">@{{ product.description }}</td>
                            <td class="text-center align-middle">@{{ product.category.name }}</td>
                            <td class="row justify-content-center">
                                <div class="card" style="width: 4rem">
                                    <img v-if="product.image_path != 'default'" class="card-img-top"
                                        :src="'/' + product.image_path">
                                    <img v-else class="card-img-top" :src="'/storage/default.jpg'">
                                </div>
                            </td>
                            <td class="text-center align-middle">@{{ product.qty }}</td>
                            <td class="text-center align-middle">Rp. @{{ numberFormat(product.price) }}</td>

                            @if (Auth::user()->role != 'employee')
                                <td class="align-middle text-center">
                                    <a :href="actionUrl + '/' + product.id + '/edit'">
                                        <button class="btn btn-warning btn-sm" title="Edit" style="margin-bottom: 5px">
                                            <i class="fas fa-edit "></i>
                                        </button>
                                    </a>

                                    <form :action="actionUrl + '/' + product.id" method="POST">
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
                    actionUrl: '{{ route('products.index') }}',
                    products: {!! json_encode($products) !!},
                }
            },

            mounted() {
                $('#datatable').DataTable()
            },

            methods: {
                numberFormat(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },
            }

        }).mount('#controller')
    </script>

@endsection
