@extends('layouts.admin')
@section('title', 'Transaction Details')

@section('css')
    <style>
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
@section('content')
    <div id="controller">
        <div class="row justify-content-around">
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Detail Transaction</h3>
                    </div>

                    <form>
                        <div class="grid-container">
                            <div class="grid-form">
                                <label>ID Transaction</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled type="text" class="form-control-plaintext"
                                        value="{{ $transaction->id }}">
                                </div>
                            </div>

                            <div class="grid-form">
                                <label>DateTime Transaction</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled type="text" class="form-control-plaintext"
                                        value="{{ date('Y-m-d', strtotime($transaction->date_tx)) . ', Time ' . date('H:m:s', strtotime($transaction->date_tx)) }}">
                                </div>
                            </div>

                            <div class="grid-form">
                                <label>Name Employee</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled class="form-control-plaintext" value="{{ $transaction->user->name }}">
                                </div>
                            </div>

                            <div class="grid-form">
                                <label>Name Customer</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled class="form-control-plaintext"
                                        value="{{ $transaction->member ? $transaction->member->name . ' (Member)' : $transaction->name_cust }}">
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
                                            @foreach ($transaction->products as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                    <td>Rp. {{ number_format($product->price, 0, '', '.') }}</td>
                                                    <td>{{ $product->pivot->qty }}</td>
                                                    <td>Rp. {{ number_format($product->pivot->price, 0, '', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="grid-form">
                                <label>Total Payment</label>
                                <div class="col-sm-12" style="background-color: rgb(194, 184, 184); border-radius: 4px;">
                                    <input disabled class="form-control-plaintext"
                                        value="Rp. {{ number_format($transaction->total, 0, '', '.') }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <a href="{{ route('transactions.index') }}">
                                <button type="button" class="btn btn-default">
                                    <span>Back</span>
                                </button>
                            </a>

                            <button
                                @click="printPage('{{ route('transactions.index') }}' + '/' + transaction.id + '/receipt')"
                                type="button" class="btn btn-primary float-right">
                                <span>Print</span>
                            </button>
                        </div>
                        <div id="printerDiv" style="display:none"></div>
                    </form>
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
                    transaction: {!! json_encode($transaction) !!}
                }
            },

            methods: {
                printPage(url) {
                    let div = document.getElementById("printerDiv");
                    div.innerHTML = '<iframe src="' + url + '" onload="this.contentWindow.print();"></iframe>';
                }
            }

        }).mount('#controller')
    </script>
@endsection
