@extends('layouts.admin')
@section('title', 'Halaman Utama')
@section('content')
    <div id="controller">
        <div class="card-header">
            <div class="row justify-content-between">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Select Category</label>
                        <select name="categories" class="form-control" @change="getValFilter">
                            <option value="0" selected>All Categories</option>
                            <option v-for="category in categories" :value="category.id">@{{ category.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Search Produk</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" autocomplete="off" placeholder="Search by name"
                            v-model="search">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ displayMessage() }}

            <div class="row">
                <div class="col-md-3" v-for="product in filteredProduct">
                    <div class="card text-center" style="width: 13rem">
                        <img v-if="product.image_path != 'default'" :src="'/' + product.image_path" alt="Coffee Shop"
                            class="card-img-top">
                        <img v-else :src="'/storage/default.jpg'" alt="Coffee Shop" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">@{{ product.name }}</h5>
                            <p v-if="!product.qty" class="card-text text-danger pt-3" style="height: 40px;">
                                <span>(Out of stock)</span>
                            </p>
                            <p v-else class="card-text pt-3" style="height: 40px;">
                                <span>Quantity : @{{ product.qty }}</span>
                            </p>
                            <p class="card-text">@{{ product.description ? product.description : 'No Description' }} </p>
                            <p class="card-text">Rp. @{{ numberFormat(product.price) }}</p>
                            <form :action="'{{ url('/add-to-cart') }}' + '/' + product.id">
                                <button type="submit" class="btn btn-primary btn-sm" :disabled="!product.qty">Add to
                                    cart</button>
                            </form>
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
                    products: {!! json_encode($products) !!},
                    categories: {!! json_encode($categories) !!},
                    current: null,
                    search: '',
                }
            },

            mounted() {

            },

            methods: {
                numberFormat(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },
                getValFilter() {
                    const data = $('select[name=categories]').val()
                    this.current = data
                }
            },

            computed: {
                filteredProduct() {
                    if (this.current != 0 && this.current)
                        return this.products.filter(product => {
                            return product.category_id == this.current &&
                                product.name.toLowerCase().includes(this.search.toLowerCase())
                        })
                    else
                        return this.products.filter(product => {
                            return product.name.toLowerCase().includes(this.search.toLowerCase())
                        })
                }
            }
        }).mount('#controller')
    </script>
@endsection
