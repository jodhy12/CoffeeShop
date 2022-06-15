@extends('layouts.admin')
@section('title', 'Halaman Utama')
@section('content')
    <div id="controller">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3">
                    Category Filter
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ displayMessage() }}

            <div class="row">
                <div class="col-md-3" v-for="(product,key) in products.data">
                    <div class="card text-center" style="width: 13rem">
                        <img v-if="exists[key]" :src="'/' + product.image_path" alt="Coffee Shop" class="card-img-top">
                        <img v-else :src="'/storage/default.jpg'" alt="Coffee Shop" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">@{{ product.name }}</h5>
                            <p class="card-text text-danger pt-3" style="height: 40px;">
                                <span v-if="!product.qty">(Out of stock)</span>
                            </p>
                            <p class="card-text">@{{ product.description ? product.description : 'No Description' }} </p>
                            <p class="card-text">Rp. @{{ numberFormat(product.price) }}</p>
                            <a :href="'{{ url('/add-to-cart') }}' + '/' + product.id">
                                <button class="btn btn-primary btn-sm" :disabled="!product.qty">Add to cart</button>
                            </a>

                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer clearfix" v-if="products.data > products.per_page">
                <ul class="pagination justify-content-center">
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
                    urlPage: 'http://localhost:8000/home?page=',
                    products: {!! json_encode($products) !!},
                    exists: {!! json_encode($exists) !!},
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
