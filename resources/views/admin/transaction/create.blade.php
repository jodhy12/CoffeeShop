@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
@endsection

@section('title', 'New Transaction')
@section('content')
    <div id="controller">
        <div class="card-header">
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Create a Transaction
                                </h3>
                            </div>
                            <div class="card-body">
                                <input type="hidden" value="{{ dateGMT(date('Y-m-d H:i:s')) }}" name="date_tx">
                                <div class="form-check">
                                    <input id="checkMember" type="checkbox" class="form-check-input">
                                    <label class="form-check-label">Apakah Seorang Member ?</label>
                                </div>

                                <div class="form-group" style="padding: 10px 0 0 0">
                                    <label>Name Employee</label>
                                    <input readonly type="text" class="form-control" value="{{ Auth::user()->name }}">
                                    <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                                </div>

                                <div id="guess" class="form-group" style="padding: 10px 0 0 0">
                                    <label>Customer Name</label>
                                    <input required type="text" class="form-control" placeholder="Enter Name"
                                        name="name_cust">
                                </div>

                                <div hidden id="member" class="form-group" style="padding: 10px 0 0 0">
                                    <label>Member Name</label>
                                    <select name="member_id" class="form-control select2bs4" style="width:100%" disabled>
                                        <option disabled selected> Choose Member</option>
                                        <option :value="member.id" v-for="member in filteredMember">
                                            @{{ member.name }}
                                        </option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Product</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(cart, key, index) in carts">
                                <td class="text-center align-middle">@{{ index + 1 }}</td>
                                <td class="row justify-content-center">
                                    <div class="card" style="width: 7rem">
                                        <img v-if="cart.image_path != 'default'" class="card-img-top"
                                            :src="'/' + cart.image_path">
                                        <img v-else class="card-img-top" :src="'/storage/default.jpg'">
                                    </div>
                                </td>
                                <td class="align-middle">@{{ cart.name }}</td>
                                <td class="text-center align-middle">Rp. @{{ numberFormat(cart.price) }}</td>
                                <td class="text-center align-middle">
                                    <input readonly required type="number" :value="cart.qty"
                                        style="width: 50px; border:none; border-radius:3px;">
                                </td>
                                <td class="text-center align-middle">Rp. @{{ numberFormat(cart.qty * cart.price) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">Total</td>
                                <td colspan="2" class="text-center">Rp.
                                    @{{ numberFormat(total) }}</td>
                                <input type="hidden" :value="total" name="total">
                            </tr>
                            <tr>
                                <td colspan="6" class="text-right">
                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
        </div>

    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(() => {
            $('.select2bs4').select2()
        })

        const {
            createApp
        } = Vue

        createApp({
            data() {
                return {
                    carts: {!! json_encode($carts) !!},
                    members: {!! json_encode($members) !!},
                    subTotal: [],
                    total: 0
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
                }
            },

            computed: {
                filteredMember() {
                    return this.members.filter(member => member.status != 0)
                }
            }

        }).mount('#controller')
    </script>
    <script>
        $('#checkMember').on('change', () => {
            let checked = $('#checkMember').prop('checked')
            if (checked) {
                $('input[name=name_cust]').prop('disabled', true)
                $('input[name=name_cust]').prop('required', false)
                $('input[name=name_cust]').val('')
                $('#guess').prop('hidden', true)
                $('.select2bs4').prop('disabled', false)
                $('.select2bs4').prop('required', true)
                $('#member').prop('hidden', false)
            } else {
                $('input[name=name_cust]').prop('disabled', false)
                $('input[name=name_cust]').prop('required', true)
                $('#guess').prop('hidden', false)
                $('.select2bs4').prop('disabled', true)
                $('.select2bs4').prop('required', false)
                $('#member').prop('hidden', true)
            }
        })
    </script>
@endsection
