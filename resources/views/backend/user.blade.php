@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-success mb-2 add-user">
                        Add
                    </button>
                    <table class="table table-display tuser">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div data-backdrop="static" class="modal fade modal-user" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal form-user">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="hidden" value="" name="id" />
                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                                {{-- <small class="text-danger password-hidden">leave blank if not replaced</small> --}}
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-outline-success user-save">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script>
        "use strict";
        $(document).ajaxError(function(event, jqxhr, settings, exception) {
            if (exception == 'Unauthorized') {
                Swal.fire({
                    title: 'Your session has ended',
                    text: "You will be redirected to the login page",
                    icon: 'info',
                    confirmButtonColor: '#4caf50',
                    confirmButtonText: 'OK!',
                }).then((result) => {
                    if (result.value) {
                        window.location.href = '/login';
                    }
                });
            }
        });
        $.fn.dataTable.ext.errMode = 'none';
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const table = $('.tuser').DataTable({
                responsive: true,
                serverSide: true,
                ajax: "{{ route('admin.users') }}",
                initComplete: function() {
                    $('select[name="verif_tabel_length"]').css('display', 'inherit');
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                columnDefs: [{
                    targets: [1, 2],
                    className: 'dt-left'
                }, {
                    targets: [0, 3],
                    className: 'dt-right',
                    orderable: false,
                    searchable: false
                }, {
                    width: "10%",
                    targets: [0]
                }, {
                    width: "30%",
                    targets: [2]
                }, {
                    width: "15%",
                    targets: [3]
                }]

            });

            $(".form-user").on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: "{{ route('admin.users.store') }}",
                    data: form.serialize(),
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status) {
                            Swal.fire({
                                type: 'success',
                                title: 'Successed',
                                text: 'Create user done',
                            }).then((result) => {
                                table.ajax.reload(null, false);
                                $(".modal-user").modal("hide");
                            });
                        } else {
                            Swal.fire("Failed", JSON.stringify(data.message),
                                'error');
                        }

                    },
                    error: function(jqXHR, status, error) {
                        Swal.fire(error, 'There is an error, contact the admin.', 'error');
                    }
                });
            });

            $('.add-user').click(function() {
                $("input[name=id]").val("");
                $(".form-user")[0].reset();
                $(".user-save").text("Save");
                $(".modal-user").on("shown.bs.modal", function() {
                    $("input[name=name]").focus();
                });
                $(".modal-user").modal("show");
                $(".modal-title").text("Add User");
            });

            $(".modal-user .user-save").on("click", function(e) {
                $(".form-user").submit();
            });

            $(document).on('click', '.user-edit', function(e) {
                $.ajax({
                    url: "{{ route('admin.users.edit') }}",
                    data: {
                        id: $(this).data('id')
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            $(".form-user")[0].reset();
                            $("input[name=id]").val(data.user.id);
                            $("input[name=name]").val(data.user.name);
                            $("input[name=email]").val(data.user.email);
                            $("input[name=password]").attr("required", false);
                            $("input[name=password]").attr("placeholder",
                                "leave blank if not replaced");
                            $(".user-save").text("Update");
                            $(".modal-user").on("shown.bs.modal", function() {
                                $("input[name=name]").focus();
                            });
                            $(".modal-user").modal("show");
                            $(".modal-title").text("Edit User");
                        }
                    }
                });
            });

            $(document).on('click', '.user-delete', function(e) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Data will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Delete Data!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('admin.users.destroy') }}",
                            type: 'POST',
                            data: {
                                id: $(this).data('id')
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data.status == true) {
                                    Swal.fire('Deleted!', 'Data deleted successfully.',
                                        'success');
                                    table.ajax.reload(null, false);
                                } else {
                                    Swal.fire("Failed",
                                        'There is an error, contact the admin.',
                                        'error');
                                }
                            }
                        });

                    }
                })
            });

        });

    </script>
@stop
