@extends('layouts.admin')

@section('title', 'Daily Report')
@section('content')
    <div id="controller">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Search Date</label>
                                    <input type="date" class="form-control" name="date" :value="reqDate">
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
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Time</th>
                                        <th class="text-center">Tx ID</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Total Qty</th>
                                        <th class="text-center">Total Payment</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td id="income" colspan="7" style="text-align: right !important"></td>
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
        const apiUrl = '{{ route('apiDailyReport') }}'
        const columns = [{
                data: 'DT_RowIndex',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'date',
                render(data) {
                    return controller.getDateFormat(data)
                },
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'time',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'tx_id',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'name',
                class: 'text-left',
                orderable: true,
            },
            {
                data: 'price',
                render: data => {
                    return 'Rp. ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                },
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'qty',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'totalPayment',
                render: data => {
                    return 'Rp. ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },
                class: 'text-left',
                orderable: true,
            },
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
                    reqDate: {!! json_encode($reqDate) !!}
                }
            },
            mounted() {
                this.dataTables(this.reqDate)
            },
            methods: {
                dataTables(reqDate) {
                    let url = ''
                    if (reqDate) {
                        url = this.apiUrl + '?date=' + reqDate
                    } else {
                        url = this.apiUrl
                    }
                    const _this = this
                    _this.table = $('#datatable').DataTable({
                        ajax: {
                            url: url,
                            type: 'GET'
                        },
                        columns,
                        searching: false,
                        autoWidth: false,
                    }).on('xhr', () => {
                        this.transactions = _this.table.ajax.json().data
                        this.total = _this.table.ajax.json().total
                    })
                    if (reqDate) {
                        $('#dateText').val('Date : ' + this.getDateFormat(reqDate) + '')
                        $('#income').html('Total Income at ' + this.getDateFormat(reqDate) + ' : ')
                    } else {
                        $('#dateText').val('Date : {!! dateGMT7(date('Y-m-d H:i:s')) !!}')
                        $('#income').html('Total Income at {!! dateGMT7(date('Y-m-d H:i:s')) !!} : ')
                    }

                },

                handleSearch() {
                    const date = $('input[name=date]').val()
                    if (date) {
                        this.table.ajax.url(apiUrl + '?date=' + date).load()
                        $('#dateText').val('Date : ' + this.getDateFormat(date))
                        $('#income').html('Total Income at ' + this.getDateFormat(date) + ' : ')
                    } else {
                        this.table.ajax.url(apiUrl).load()
                    }
                },

                numberWithSpaces(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                },

                getDateFormat(x) {
                    const d = new Date(x)
                    let getYear = d.getFullYear() < 10 ? '0' + d.getFullYear() : d.getFullYear()
                    let getMonth = d.getMonth() + 1 < 10 ? '0' + d.getMonth() : d.getMonth()
                    let getDate = d.getDate() < 10 ? '0' + d.getDate() : d.getDate()

                    const date = getDate + '-' + getMonth + '-' + getYear

                    return date;
                },
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
