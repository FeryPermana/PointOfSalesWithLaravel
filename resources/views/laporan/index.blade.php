@extends('layouts.master')
@section('title')
    Laporan Pendapatan {{ tanggal_indonesia($tanggalAwal, false) }} s/d {{ tanggal_indonesia($tanggalAkhir, false) }}
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Laporan Pendapatan</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="updatePeriode()" class="btn btn-info btn-flat"><i
                            class="fa fa-plus-circle"></i> Ubah Periode</button>
                    <a href="{{ route('laporan.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank" class="btn btn-success btn-flat"><i
                            class="fa fa-file-excel-o"></i> Export PDF</a>
                </div>
                <div class="box-body table-responsive">
                        @csrf
                        <table id="tabel" class="table table-stiped table-bordered">
                            <thead>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>Penjualan</th>
                                <th>Pembelian</th>
                                <th>Pengeluaran</th>
                                <th>Pendapatan</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@includeIf('laporan.form')
@push('scripts')
<script src="{{ asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        let table;

        $(document).ready(function() {
            table = $('#tabel').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('laporan.data', [$tanggalAwal, $tanggalAkhir]) }}",
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'penjualan'
                    },
                    {
                        data: 'pembelian'
                    },
                    {
                        data: 'pengeluaran'
                    },
                    {
                        data: 'pendapatan'
                    },
                ],
                dom: 'Brt',
                bSort: false,
                bPaginate: false
            });


            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        function updatePeriode() {
            $('#modal-form').modal('show');
        }

    </script>
@endpush
