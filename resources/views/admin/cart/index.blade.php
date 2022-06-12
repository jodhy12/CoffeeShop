@extends('layouts.admin')

@section('title', 'Cart List')

@section('content')
    <div id="controller">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        Add Product to Cart
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
                        <th class="text-center">Product</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Subtotal</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(cart, key, index) in carts">
                        <td class="text-center align-middle">@{{ index + 1 }}</td>
                        <td class="row justify-content-center">
                            <div class="card" style="width: 7rem">
                                <img class="card-img-top" :src="'/' + cart.image_path">
                            </div>
                        </td>
                        <td class="align-middle">@{{ cart.name }}</td>
                        <td class="text-center align-middle">Rp. @{{ numberFormat(cart.price) }}</td>
                        <td class="text-center align-middle">
                            <input type="number" name="qty" :value="cart.qty"
                                style="width: 50px; border:none; border-radius:3px;" @click.once="handleQty(key, $event)">
                        </td>
                        <td class="text-center align-middle">Rp. @{{ numberFormat(cart.qty * cart.price) }}</td>
                        <td class="align-middle text-center">
                            <form :action="deleteUrl" method="POST" @submit.prevent="handleSubmit(key)">
                                <button type="submit" title="Delete" class="btn btn-danger btn-sm"><i
                                        class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">Total</td>
                        <td colspan="2" class="text-center">Rp. @{{ numberFormat(total) }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right">
                            <a href="{{ route('transactions.create') }}"><button
                                    class="btn btn-primary">Checkout</button></a>
                        </td>
                    </tr>
                </tfoot>
            </table>
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
                    carts: {!! json_encode($carts) !!},
                    subTotal: [],
                    total: 0,
                    updateUrl: '{{ route('updateCart') }}',
                    deleteUrl: '{{ route('removeCart') }}',
                    qty: null
                }
            },

            mounted() {
                this.countTotal()
            },

            methods: {
                numberFormat(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                countTotal() {
                    if (this.carts.length != 0) {
                        for (let index in this.carts) {
                            let cart = this.carts[index]
                            this.subTotal.push(cart.qty * cart.price)
                        }

                        this.total = this.subTotal.reduce((a, b) => a + b)
                    }
                },
                handleQty(id, e) {
                    let qty = e.target.value
                    if (qty == 0) {
                        $.ajax({
                            url: this.deleteUrl,
                            method: 'delete',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id
                            },
                            success: resp => {
                                window.location.reload();
                            }
                        })
                    } else {
                        $.ajax({
                            url: this.updateUrl,
                            method: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                qty: qty
                            },
                            success: resp => {
                                window.location.reload();
                            }
                        })
                    }
                },

                handleSubmit(id) {
                    $.ajax({
                        url: this.deleteUrl,
                        method: 'delete',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: resp => {
                            window.location.reload();
                        }
                    })
                }
            }

        }).mount('#controller')
    </script>

@endsection
