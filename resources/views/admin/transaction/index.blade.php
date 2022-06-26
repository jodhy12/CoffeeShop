@extends('layouts.admin')
@section('title', ' Transaction')
@section('content')
    <div id="controller">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default align-items-center">
                    <div class="card-header">
                        @if (Auth::user()->role != 'admin')
                            <h3 class="card-title">
                                List of transaction Period <b>{{ dateGMT7(date('Y-m-d H:i:s')) }}</b>
                            </h3>
                        @else
                            <h3 class="card-title">
                                <b>All Transactions</b>
                            </h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {{ displayMessage() }}
                <table id="datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">ID Tx</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Time</th>
                            <th class="text-center">Name Employee</th>
                            <th class="text-center">Name Customer</th>
                            <th class="text-center">Member</th>
                            <th class="text-center">Total Item</th>
                            <th class="text-center">Total Payment</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(transaction, value) in transactions">
                            <td class="text-center">@{{ value + 1 }}</td>
                            <td class="text-center">@{{ transaction.id }}</td>
                            <td class="text-center">@{{ getDateFormat(transaction.date_tx) }}</td>
                            <td class="text-center">@{{ getTimeFormat(transaction.date_tx) }}</td>
                            <td class="text-center">@{{ transaction.user.name }}</td>
                            <td>@{{ transaction.member ? transaction.member.name : transaction.name_cust }}</td>
                            <td class="text-center">@{{ transaction.member ? 'Yes' : 'No' }}</td>
                            <td class="text-center">
                                @{{ pivotQty[value].total }}
                            </td>
                            <td class="text-center">Rp. @{{ numberFormat(transaction.total) }}</td>
                            <td class="text-center">
                                <a :href="'{{ route('transactions.index') }}' + '/' + transaction.id" title="Detail">
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </a>
                                <a @click="getDetails(transaction)" href="#" title="Detail">
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fa fa-eye"></i>
                                        Modal
                                    </button>
                                </a>
                                <a @click="printPage('{{ route('transactions.index') }}' + '/' + transaction.id + '/receipt')"
                                    href="#" title="Print">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fa fa-print"></i>
                                    </button>
                                </a>
                                <div id="printerDiv" style="display:none"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <div class="modal-title">Detail Transaction</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body" v-if="transaction">
                        <div class="grid-container">
                            <div class="grid-form">
                                <label>ID Transaction</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled type="text" class="form-control-plaintext" :value="transaction.id">
                                </div>
                            </div>

                            <div class="grid-form">
                                <label>Datetime Transaction</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled type="text" class="form-control-plaintext"
                                        :value="getDateFormat(transaction.date_tx) + ', Time ' + getTimeFormat(transaction
                                            .date_tx)">
                                </div>
                            </div>

                            <div class="grid-form">
                                <label>Name Employee</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled class="form-control-plaintext" :value="transaction.user.name">
                                </div>
                            </div>

                            <div class="grid-form">
                                <label>Name Customer</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled class="form-control-plaintext"
                                        :value="transaction.member ? transaction.member.name + ' (Member)' : transaction
                                            .name_cust +
                                            ' (Not Member)'">
                                </div>
                            </div>

                            <div class="grid-form">
                                <label>Product</label>
                                <div class="card-body table-responsive p-0" style="height:200px;">
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Harga</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="product in transaction.products">
                                                <td>@{{ product.name }}</td>
                                                <td>Rp. @{{ numberFormat(product.price) }}</td>
                                                <td>@{{ product.pivot.qty }}</td>
                                                <td>Rp. @{{ numberFormat(product.pivot.price) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="grid-form">
                                <label>Total Payment</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled class="form-control-plaintext"
                                        :value="'Rp. ' + numberFormat(transaction.total)">
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer justify-content-between">
                            <button @click="modalClose" type="button" class="btn btn-default">
                                <span>Back</span>
                            </button>
                            <button
                                @click="printPage('{{ route('transactions.index') }}' + '/' + transaction.id + '/receipt')"
                                type="button" class="btn btn-primary">
                                <span>Print</span>
                            </button>
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
                    actionUrl: '{{ route('transactions.index') }}',
                    transactions: {!! json_encode($transactions) !!},
                    pivotQty: {!! json_encode($pivotQty) !!},
                    transaction: null,
                }
            },

            mounted() {
                $('#datatable').DataTable({
                    autoWidth: false
                })
            },

            methods: {
                getDetails(transaction) {
                    this.transaction = transaction
                    $('#modal-lg').modal()
                },

                modalClose() {
                    $('#modal-lg').modal('hide')
                },

                getDateFormat(x) {
                    const d = new Date(x)
                    let getYear = d.getFullYear() < 10 ? '0' + d.getFullYear() : d.getFullYear()
                    let getMonth = d.getMonth() + 1 < 10 ? '0' + d.getMonth() : d.getMonth()
                    let getDate = d.getDate() < 10 ? '0' + d.getDate() : d.getDate()

                    const date = getDate + '-' + getMonth + '-' + getYear

                    return date;
                },

                getTimeFormat(x) {
                    const d = new Date(x)
                    let getHours = d.getHours() < 10 ? '0' + d.getHours() : d.getHours()
                    let getMinutes = d.getMinutes() < 10 ? '0' + d.getMinutes() : d.getMinutes()
                    let getSeconds = d.getSeconds() < 10 ? '0' + d.getSeconds() : d.getSeconds()

                    const time = getHours + ':' + getMinutes + ':' + getSeconds;
                    return time;
                },

                numberFormat(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                printPage(url) {
                    let div = document.getElementById("printerDiv");
                    div.innerHTML = '<iframe src="' + url + '" onload="this.contentWindow.print();"></iframe>';
                }

            }
        }).mount('#controller')
    </script>
    <style>
        td a button {
            margin: 3px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 1fr;
            margin: 20px 10px;
            gap: 20px;
        }

        .grid-container label {
            margin: 0;
        }

        .grid-form {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            padding-bottom: 10px;
            align-items: center;
        }
    </style>
@endsection
