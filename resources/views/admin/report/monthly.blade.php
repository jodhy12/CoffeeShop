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
                                    <select name="month" class="form-control" @change="handleChange">
                                        <option :selected="thisMonth() == month" v-for="month in months"
                                            :value="month">
                                            @{{ month }}
                                        </option>
                                    </select>
                                </div>
                                <div class="row flex-nowrap">
                                    <input id="dateText" type="text" class="form-control" disabled>
                                    <button id="btnClick" @click.prevent="handleSearch" type="button"
                                        class="btn btn-primary btn-sm">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 pt-3 mx-auto" v-if="label && dataDays">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Data Transaction</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="txChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                                        <th class="text-center">Many Transaction</th>
                                        <th class="text-center">Total Payment</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td id="income" colspan="3" style="text-align: right !important"></td>
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
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const apiUrl = '{{ route('apiMonthlyReport') }}'
        const dailyUrl = '{{ route('dailyReport') }}'
        const columns = [{
                data: 'DT_RowIndex',
                class: 'text-center',
                orderable: true,
            },
            {
                data: 'date',
                render(data) {
                    return '<a href="' + dailyUrl + '?date=' + data + '">' + controller.getDateFormat(data) + '</a>'
                },
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
                    months,
                    month: null,
                    label: null,
                    dataDays: null,
                }
            },

            mounted() {
                this.dataTables()
            },

            updated() {
                this.lineChart()
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
                        this.label = _this.table.ajax.json().label
                        this.dataDays = _this.table.ajax.json().dataDays
                    })

                    this.month = this.thisMonth()
                    $('#dateText').val('Month : ' + this.month + ' 2022')
                    $('#income').html('Total Income Period ' + this.month + ' 2022' + ' : ')
                    $('#btnClick').prop('disabled', true)

                },

                handleChange() {
                    const month = $('select[name=month]').val()
                    if (this.month == month) {
                        $('#btnClick').prop('disabled', true)
                    } else {
                        $('#btnClick').prop('disabled', false)
                    }
                },

                handleSearch() {
                    this.month = $('select[name=month]').val()
                    if (this.month) {
                        this.table.ajax.url(apiUrl + '?month=' + this.month).load()
                        $('#dateText').val('Month : ' + this.month + ' 2022')
                        $('#income').html('Total Income Period ' + this.month + ' 2022' + ' : ')
                    } else {
                        this.table.ajax.url(apiUrl).load()
                    }

                    this.txChart.destroy()
                    $('#btnClick').prop('disabled', true)

                },

                getDateFormat(x) {
                    const d = new Date(x)
                    let getYear = d.getFullYear() < 10 ? '0' + d.getFullYear() : d.getFullYear()
                    let getMonth = d.getMonth() + 1 < 10 ? '0' + d.getMonth() : d.getMonth()
                    let getDate = d.getDate() < 10 ? '0' + d.getDate() : d.getDate()

                    const date = getDate + '-' + getMonth + '-' + getYear

                    return date;
                },

                numberWithSpaces(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                },

                thisMonth() {
                    const d = new Date()
                    return months[d.getMonth()]
                },

                lineChart() {
                    if (this.label && this.dataDays) {
                        const _this = this
                        const ctx = document.getElementById('txChart').getContext('2d')

                        const labels = this.label
                        const data = {
                            labels: labels,
                            datasets: [{
                                label: 'Report Period ' + this.month,
                                backgroundColor: 'rgb(255, 99, 132)',
                                borderColor: 'rgb(255, 99, 132)',
                                data: this.dataDays,
                            }]
                        }

                        const config = {
                            type: 'line',
                            data: data,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        }
                        _this.txChart = new Chart(ctx, config)
                    }
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
