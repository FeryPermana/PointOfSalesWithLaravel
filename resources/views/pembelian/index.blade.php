@extends('layouts.master')
@section('title')
    Transaksi Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm()" class="btn btn-success btn-flat"><i class="fa fa-plus-circle"></i> Transaksi
                        Baru</button>
                    @empty(!session('id_pembelian'))
                        <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info btn-flat"><i
                                class="fa fa-edit"></i>Transaksi Aktif</a>
                    @endempty
                </div>
                <div class="box-body table-responsive">
                        <table id="table" class="table table-pembelian table-stiped table-bordered">
                            <thead>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Diskon</th>
                                <th>Total Bayar</th>
                                <th width="15%"><i class="fa fa-cog"></i>Aksi</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@includeIf('pembelian.supplier')
@includeIf('pembelian.detail')
@push('scripts')
    <script>
        let table;

        $(document).ready(function() {
            table = $('.table-pembelian').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('pembelian.data') }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'supplier'
                    },
                    {
                        data: 'total_item'
                    },
                    {
                        data: 'total_harga'
                    },
                    {
                        data: 'diskon'
                    },
                    {
                        data: 'bayar'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });


            $('.table-supplier').DataTable();


            tabledetail = $('#table-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'Brt',
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'kode_produk'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'harga_beli'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'subtotal'
                    },
                ]
            })
        });

        function addForm() {
            $('#modal-supplier').modal('show');
        }

        function showDetail(url) {
            $('#modal-detail').modal('show');
            tabledetail.ajax.url(url);
            tabledetail.ajax.reload();
        }

        function deleteData(id) {
            var id = id;
            var token = $("meta[name='csrf-token']").attr("content");
            swal({
                title: "APAKAH KAMU YAKIN ?",
                text: "INGIN MENGHAPUS SUPPLIER INI!",
                icon: "warning",
                buttons: [
                    'TIDAK',
                    'YA'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    //ajax delete
                    jQuery.ajax({
                        url: "{{ route('pembelian.index') }}/" + id,
                        data: {
                            "id": id,
                            "_token": token
                        },
                        type: 'DELETE',
                        success: function(response) {
                            if (response.status == "success") {
                                swal({
                                    title: 'BERHASIL!',
                                    text: 'SUPPLIER BERHASIL DIHAPUS!',
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    buttons: false,
                                }).then(function() {
                                    table.ajax.reload();
                                });
                            } else {
                                swal({
                                    title: 'GAGAL!',
                                    text: 'SUPPLIER GAGAL DIHAPUS!',
                                    icon: 'error',
                                    timer: 1000,
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    buttons: false,
                                }).then(function() {
                                    table.ajax.reload();
                                });
                            }
                        }
                    });
                } else {
                    return true;
                }
            })
        }

        function cetakMember(url) {
            if ($('input:checked').length < 1) {
                swal({
                    title: 'GAGAL!',
                    text: ' PILIH DATA YANG AKAN DICETAK!',
                    icon: 'error',
                    showConfirmButton: true,
                    showCancelButton: false,
                })
                return;
            } else {
                $('.form-supplier')
                    .attr('target', '_blank')
                    .attr('action', url)
                    .submit();
            }
        }
    </script>
@endpush
