@extends('layouts.master')
@section('title')
    Supplier
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Supplier</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <button onclick="addForm('{{ route('supplier.store') }}')" class="btn btn-success btn-flat"><i
                            class="fa fa-plus-circle"></i> Tambah</button>
                </div>
                <div class="box-body table-responsive">
                    <form action="" method="post" class="form-supplier">
                        @csrf
                        <table id="tabel" class="table table-stiped table-bordered">
                            <thead>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th width="15%"><i class="fa fa-cog"></i>Aksi</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@includeIf('supplier.form')
@push('scripts')
    <script>
        let table;

        $(document).ready(function() {
            table = $('#tabel').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('supplier.data') }}",
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'telepon'
                    },
                    {
                        data: 'alamat'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ]
            });

            $("#modal-form").validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                        .done((response) => {
                            swal({
                                type: "success",
                                icon: "success",
                                title: "BERHASIL!",
                                text: response,
                                timer: 1500,
                                showConfirmButton: false,
                                showCancelButton: false,
                                buttons: false,
                            });
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            swal({
                                type: "warning",
                                icon: "error",
                                title: "Tidak dapat menyimpan data !",
                                text: "Periksa data supplier apakah sudah terdaftar atau belum",
                                showConfirmButton: true,
                                showCancelButton: false,
                            });
                            return;
                        })
                }
            })

            $('[name=select_all]').on('click', function() {
                $(':checkbox').prop('checked', this.checked);
            });
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Member');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit supplier');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama]').focus();
            // url show sama url update itu sama
            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama]').val(response.nama);
                    $('#modal-form [name=telepon]').val(response.telepon);
                    $('#modal-form [name=alamat]').val(response.alamat);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
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
                        url: "{{ route('supplier.index') }}/" + id,
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
        function cetakMember(url)
        {
            if ($('input:checked').length < 1) {
                swal({
                    title: 'GAGAL!',
                    text: ' PILIH DATA YANG AKAN DICETAK!',
                    icon: 'error',
                    showConfirmButton: true,
                    showCancelButton: false,
                })
                return;
            }else{
              $('.form-supplier')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
            }
        }
    </script>
@endpush
