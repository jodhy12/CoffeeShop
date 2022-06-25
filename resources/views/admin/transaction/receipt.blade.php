<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'PT Sans', sans-serif;
        }

        @page {
            size: 2.8in 11in;
            margin-top: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
        }

        table {
            width: 100%;
        }

        tr {
            width: 100%;

        }

        h1 {
            text-align: center;
            vertical-align: middle;
        }

        #logo {
            width: 60%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            padding: 5px;
            margin: 2px;
            display: block;
            margin: 0 auto;
        }

        header {
            width: 100%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            vertical-align: middle;
        }

        .items thead {
            text-align: center;
        }

        .center-align {
            text-align: center;
        }

        .bill-details td {
            font-size: 12px;
        }

        .receipt {
            font-size: medium;
        }

        .items .heading {
            font-size: 12.5px;
            text-transform: uppercase;
            border-top: 1px solid black;
            margin-bottom: 4px;
            border-bottom: 1px solid black;
            vertical-align: middle;
        }

        .items thead tr th:first-child,
        .items tbody tr td:first-child {
            width: 30%;
            min-width: 47%;
            max-width: 47%;
            word-break: break-all;
            text-align: left;
        }

        .items td {
            font-size: 12px;
            text-align: right;
            vertical-align: bottom;
        }

        .price::before {
            content: "Rp. ";
            font-family: Arial;
            text-align: right;
        }

        .sum-up {
            text-align: right !important;
        }

        .total {
            font-size: 12px;
            border-top: 1px dashed black !important;
            border-bottom: 1px dashed black !important;
        }

        .total.text,
        .total.price {
            text-align: right;
        }

        .total.price::before {
            content: "Rp. ";
        }

        .line {
            border-top: 1px solid black !important;
        }

        .heading.rate {
            width: 27%;
        }

        .heading.amount {
            width: 30%;
        }

        .heading.qty {
            width: 5%
        }

        p {
            padding: 1px;
            margin: 0;
        }

        section,
        footer {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <header>
        {{-- <div id="logo" class="media" src="{{ asset('storage/logo/logo.png') }}"></div> --}}
    </header>
    @foreach ($txDetail as $tx)
        <p>Tx Number : {{ $tx->id }}</p>
        <table class="bill-details">
            <tbody>
                <tr>
                    <td>Date : <span>{{ date('d-m-Y', strtotime($tx->date_tx)) }}, </span>
                        <span>{{ date('H:i:s', strtotime($tx->date_tx)) }}</span>
                    </td>
                </tr>

                <tr>
                    <td>Employee Name : <span>{{ $tx->user->name }}</span></td>
                </tr>
                <tr>
                    <td>Cust Name :
                        <span>{{ $tx->name_cust ? $tx->name_cust : $tx->member->name . ' (Member)' }}</span>
                    </td>
                </tr>
                <tr>
                    <th style="padding-top: 10px;" class="center-align" colspan="2">
                        <span class="receipt">Receipt</span>
                    </th>
                </tr>
            </tbody>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th class="heading name">Item</th>
                    <th class="heading qty">Qty</th>
                    <th class="heading rate">@</th>
                    <th class="heading amount">Amount</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tx->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td style="text-align: center !important">{{ $product->pivot->qty }}</td>
                        <td class="price">{{ formatRupiah($product->price) }}</td>
                        <td class="price">{{ formatRupiah($product->pivot->price) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3" class="sum-up line">Subtotal</td>
                    <td class="line price">{{ formatRupiah($tx->total) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="sum-up line">Tax</td>
                    <td class="line price">0</td>
                </tr>
                <tr>
                    <th colspan="3" class="total text">Total</th>
                    <th class="total price">{{ formatRupiah($tx->total) }}</th>
                </tr>
            </tbody>
        </table>
    @endforeach
    <section>
        <p>
            Paid by : <span>CASH</span>
        </p>
        <p style="text-align:center; padding-top:15px;">
            Thank you for your visit!
        </p>
    </section>
</body>

</html>
