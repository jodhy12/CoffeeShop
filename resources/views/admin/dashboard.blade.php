@extends('layouts.admin')
@section('title', 'Halaman Utama')
@section('content')
    <div id="controller">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    Category Search
                </div>
            </div>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="aler alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-4" v-for="product in products.data">
                    <div class="card text-center" style="width: 15rem">
                        <img :src="'/' + product.image_path" alt="Coffee Shop" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">@{{ product.name }}</h5>
                            <p class="card-text pt-3">@{{ product.description }}</p>
                            <p class="card-text">Rp. @{{ numberFormat(product.price) }}</p>
                            <a class="btn btn-primary btn-sm" :href="'http://localhost:8000/add-to-cart/' + product.id">Add
                                to
                                cart</a>
                        </div>
                    </div>
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
                    products: {!! json_encode($products) !!}
                }
            },

            mounted() {},

            methods: {
                numberFormat(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        }).mount('#controller')
    </script>
@endsection
