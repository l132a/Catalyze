@extends('adminlte::page')

@section('title', 'Post')

@section('content_header')
    <h1>Posts</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-success mb-2 add-post">
                        Add
                    </button>
                    <table class="table table-display tpost">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div data-backdrop="static" class="modal fade modal-post" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title">Add post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal form-post" enctype="multipart/form-data" method="POST">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-10">
                                <input type="hidden" value="" name="id" />
                                <input type="text" class="form-control" name="title" placeholder="Title" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select class="form-control select-category" name="category_id" style="width: 100%;">
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Content</label>
                            <div class="col-sm-10">
                                <textarea style="resize: vertical;" name="content" class="form-control content"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Image</label>
                            <div class="col-sm-10">
                                <input type="file" name="image" placeholder="Image">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Preview</label>
                            <div class="col-sm-10">
                                <img class="image_preview" src="{{ asset('images/no-image.jpg') }}" alt="preview image"
                                    style="max-height: 150px; padding: 10px; border: 1px solid #c2c2c2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <input type='radio' name='status' value='1'> Published &nbsp; <input type='radio'
                                    name='status' value='0' checked> Draft</td>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-outline-success post-save">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="{{ asset('vendor/summernote/summernote.css') }}" rel="stylesheet">
@stop

@section('js')
    <script src="{{ asset('vendor/summernote/summernote.js') }}"></script>
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
            $('.modal-post .select-category').select2({
                placeholder: "Select an option",
                ajax: {
                    url: "{{ route('admin.categories.show') }}",
                    dataType: "json",
                    processResults: function(data) {
                        if (data.status) {
                            var results = [];
                            $.each(data.category, function(index, item) {
                                results.push({
                                    id: item.id,
                                    text: item.category
                                });
                            });
                            return {
                                results: results
                            };
                        }
                    }
                }
            });

            $('.modal-post .content').summernote({
                placeholder: 'Content post',
                height: 300,
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const table = $('.tpost').DataTable({
                responsive: true,
                serverSide: true,
                ajax: "{{ route('admin.posts') }}",
                initComplete: function() {
                    $('select[name="verif_tabel_length"]').css('display', 'inherit');
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'status'
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
                    targets: [0, 4],
                    className: 'dt-right',
                    orderable: false,
                    searchable: false
                }, {
                    width: "10%",
                    targets: [0]
                }, {
                    className: 'dt-center',
                    targets: [2]
                }, {
                    width: "15%",
                    targets: [1, 3, 4]
                }]

            });

            $(".form-post").on("submit", function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: "{{ route('admin.posts.store') }}",
                    data: new FormData(this),
                    type: "POST",
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.status) {
                            Swal.fire({
                                type: 'success',
                                title: 'Successed',
                                text: 'Create post done',
                            }).then((result) => {
                                table.ajax.reload(null, false);
                                $(".modal-post").modal("hide");
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

            $('.add-post').click(function() {
                $("input[name=id]").val("");
                $(".form-post")[0].reset();
                $(".post-save").text("Save");
                $(".modal-post").on("shown.bs.modal", function() {
                    $("input[name=title]").focus();
                });
                $(".modal-post").modal("show");
                $(".modal-title").text("Add post");
            });

            $(".modal-post .post-save").on("click", function(e) {
                $(".form-post").submit();
            });

            $(document).on('click', '.post-edit', function(e) {
                $.ajax({
                    url: "{{ route('admin.posts.edit') }}",
                    data: {
                        id: $(this).data('id')
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            $(".form-post")[0].reset();
                            $("input[name=id]").val(data.post.id);
                            $("input[name=title]").val(data.post.title);
                            $('.modal-post .content').summernote('code', data.post.content);
                            $("input[name=status][value=" + data.post.status + "]").prop(
                                'checked', true);

                            if (data.post.category_id) {
                                $('.modal-post .select-category').select2("trigger", "select", {
                                    data: {
                                        id: data.post.category_id,
                                        text: data.post.category
                                    }
                                });
                            }
                            $('.image_preview').attr('src', '/images/' + data.post.image);
                            $(".post-save").text("Update");
                            $(".modal-post").on("shown.bs.modal", function() {
                                $("input[name=name]").focus();
                            });
                            $(".modal-post").modal("show");
                            $(".modal-title").text("Edit post");
                        }
                    }
                });
            });

            $(document).on('click', '.post-delete', function(e) {
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
                            url: "{{ route('admin.posts.destroy') }}",
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

            $('input[name=image]').on('change', function(data) {
                readURL(this);
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.image_preview').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

        });

    </script>
@stop
