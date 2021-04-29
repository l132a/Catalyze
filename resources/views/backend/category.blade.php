@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')
    <h1>Categories</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-success mb-2 add-category">
                        Add
                    </button>
                    <table class="table table-display tcategory">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Catgory</th>
                                <th>Slug</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Category</th>
                                <th>Slug</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div data-backdrop="static" class="modal fade modal-category" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal form-category">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <input type="hidden" value="" name="id" />
                                <input type="text" class="form-control" name="category" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Slug</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="slug" placeholder="Email" required>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-outline-success category-save">
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
            const table = $('.tcategory').DataTable({
                responsive: true,
                serverSide: true,
                ajax: "{{ route('admin.categories') }}",
                initComplete: function() {
                    $('select[name="verif_tabel_length"]').css('display', 'inherit');
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'slug'
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

            $(".form-category").on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: "{{ route('admin.categories.store') }}",
                    data: form.serialize(),
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status) {
                            Swal.fire({
                                type: 'success',
                                title: 'Successed',
                                text: 'Create category done',
                            }).then((result) => {
                                table.ajax.reload(null, false);
                                $(".modal-category").modal("hide");
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

            $('.add-category').click(function() {
                $("input[name=id]").val("");
                $(".form-category")[0].reset();
                $(".category-save").text("Save");
                $(".modal-category").on("shown.bs.modal", function() {
                    $("input[name=category]").focus();
                });
                $(".modal-category").modal("show");
                $(".modal-title").text("Add Category");
            });

            $(".modal-category .category-save").on("click", function(e) {
                $(".form-category").submit();
            });

            $(document).on('click', '.category-edit', function(e) {
                $.ajax({
                    url: "{{ route('admin.categories.edit') }}",
                    data: {
                        id: $(this).data('id')
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            $(".form-category")[0].reset();
                            $("input[name=id]").val(data.category.id);
                            $("input[name=category]").val(data.category.category);
                            $("input[name=slug]").val(data.category.slug);
                            $(".category-save").text("Update");
                            $(".modal-category").on("shown.bs.modal", function() {
                                $("input[name=name]").focus();
                            });
                            $(".modal-category").modal("show");
                            $(".modal-title").text("Edit Category");
                        }
                    }
                });
            });

            $(document).on('click', '.category-delete', function(e) {
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
                            url: "{{ route('admin.categories.destroy') }}",
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
