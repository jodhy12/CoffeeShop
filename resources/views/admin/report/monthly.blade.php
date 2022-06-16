@extends('layouts.admin')

@section('title', 'Monthly Report')
@section('content')
    <div id="controller">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Search Month</label>
                                    <select name="month" class="form-control">
                                        <option value="0" hidden>Choose Month</option>
                                        <option :selected="thisMonth() == month" v-for="month in months"
                                            :value="month">
                                            @{{ month }}
                                        </option>
                                    </select>
                                </div>
                                <div class="row flex-nowrap">
                                    <input id="dateText" type="text" class="form-control" disabled>
                                    <button @click="handleSearch" type="button"
                                        class="btn btn-primary btn-sm">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="card-body pt-3">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Transaction ID</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Many Transaction</th>
                                        <th class="text-center">Total Payment</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td id="income" colspan="4" style="text-align: right !important"></td>
                                        <td id="totalIncome" class="text-left"><b>Rp. @{{ numberWithSpaces(total) }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const apiUrl = '{{ route('apiMonthlyReport') }}'
        const columns = [{
                data: 'DT_RowIndex',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'id',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'date',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'many_tx',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'total',
                render: data => {
                    return 'Rp. ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                },
                class: 'text-left',
                orderable: true,
            },
        ]

        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
            'November', 'December'
        ]

        const {
            createApp
        } = Vue
        const controller = createApp({
            data() {
                return {
                    transactions: [],
                    apiUrl,
                    total: 0,
                    months
                }
            },
            mounted() {
                this.dataTables()
            },
            methods: {

                dataTables() {
                    const _this = this
                    _this.table = $('#datatable').DataTable({
                        ajax: {
                            url: this.apiUrl,
                            type: 'GET'
                        },
                        columns,
                        searching: false,
                        autoWidth: false,
                    }).on('xhr', () => {
                        this.transactions = _this.table.ajax.json().data
                        this.total = _this.table.ajax.json().total
                    })

                    $('#dateText').val('Month : ' + this.thisMonth())
                    $('#income').html('Total Income Period ' + this.thisMonth() + ' : ')

                },

                handleSearch() {
                    const month = $('select[name=month]').val()
                    console.log(month)
                    if (month != 0) {
                        this.table.ajax.url(apiUrl + '?month=' + month).load()
                        $('#dateText').val('Month : ' + month)
                        $('#income').html('Total Income Period ' + month + ' : ')
                    } else {
                        this.table.ajax.url(apiUrl).load()
                    }
                },

                numberWithSpaces(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                },

                thisMonth() {
                    const d = new Date()
                    return months[d.getMonth()]
                }

            },
        }).mount('#controller')
    </script>

    <!-- CSS Scoped -->
    <style>
        .row {
            margin: 0 auto;
        }

        td a.btn {
            margin: 5px;
        }
    </style>
@endsection
