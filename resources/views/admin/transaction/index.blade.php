@extends('layouts.admin')
@section('title', 'Transaction')
@section('content')
    <div id="controller">
        @if (Auth::user()->role != 'admin')
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title text-center">
                                List of transaction for date <b>{{ dateGMT7(date('Y-m-d H:i:s')) }}</b>
                            </h3>
                        </div>
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
                        </td>
                    </tr>
                </tbody>
            </table>
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
                    }
                },

                mounted() {
                    $('#datatable').DataTable({
                        autoWidth: false
                    })
                },

                methods: {
                    getDateFormat(x) {
                        const d = new Date(x)
                        let getYear = d.getFullYear()
                        let getMonth = d.getMonth() + 1
                        let getDate = d.getDate()

                        if (getYear < 10) {
                            getYear = '0' + getYear
                        }
                        if (getMonth < 10) {
                            getMonth = '0' + getMonth
                        }
                        if (getDate < 10) {
                            getDate = '0' + getDate
                        }
                        const date = getDate + '-' + getMonth + '-' + getYear
                        return date;
                    },
                    getTimeFormat(x) {
                        const d = new Date(x)
                        let getHours = d.getHours()
                        let getMinutes = d.getMinutes()
                        let getSeconds = d.getSeconds()

                        if (getHours < 10) {
                            getHours = '0' + getHours
                        }
                        if (getMinutes < 10) {
                            getMinutes = '0' + getMinutes
                        }
                        if (getSeconds < 10) {
                            getSeconds = '0' + getSeconds
                        }
                        const time = getHours + ':' + getMinutes + ':' + getSeconds;
                        return time;
                    },
                    numberFormat(x) {
                        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    },

                }
            }).mount('#controller')
        </script>

    @endsection
