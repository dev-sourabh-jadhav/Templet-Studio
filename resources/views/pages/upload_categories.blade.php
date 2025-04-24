@extends('structure.main')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire('Success!', '{{ session('success') }}', 'success');
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire('Error!', '{!! implode('<br>', $errors->all()) !!}', 'error');
        </script>
    @endif

    <div class="breadcrumb-wrapper">
        <h2 class="page-title">Add Categories</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href=/home">Home</a></li>

            <li class="breadcrumb-item active">Add Categories</li>
        </ol>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Add New Categories</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="categories_name" class="form-label">Category Name</label>
                    <input type="text" name="categories_name" id="categories_name" class="form-control" required>

                    @error('categories_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </form>
        </div>
    </div>

    <div class="card">


        <div class="card-body">


            <table id="CategoriesTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>SR No</th>
                        <th>Categories Name</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Edit Category Modal -->
    <div class="modal fade" id="CategoriesModal" tabindex="-1" aria-labelledby="CategoriesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editCategoryForm">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="CategoriesModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_categories_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="edit_categories_name" name="categories_name"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- <script>
        $(document).ready(function() {
            $('#CategoriesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "/categories/show",
                    type: "GET",
                    dataSrc: 'categories'
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'categories_name',
                        name: 'categories_name'
                    },
                    {
                        data: "id",
                        render: function(data, type, row) {
                            return `
                        <button class="btn btn-sm btn-primary editCategory" data-id="${data}"><i class="ti ti-edit-circle"></i></button>
                        <button class="btn btn-sm btn-danger deleteCategory" data-id="${data}"><i class="ti ti-trash"></i></button>
                    `;
                        }
                    },
                ]
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            const table = $('#CategoriesTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "/categories/show",
                    type: "GET",
                    dataSrc: 'categories'
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'categories_name'
                    },
                    {
                        data: "id",
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-primary editCategory" data-id="${data}" data-name="${row.categories_name}"><i class="ti ti-edit-circle"></i></button>
                                <button class="btn btn-sm btn-danger deleteCategory" data-id="${data}"><i class="ti ti-trash"></i></button>
                            `;
                        }
                    },
                ]
            });

            // Show Edit Modal
            $(document).on('click', '.editCategory', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                $('#edit_id').val(id);
                $('#edit_categories_name').val(name);
                $('#CategoriesModal').modal('show');
            });

            // Update Category AJAX
            $('#editCategoryForm').submit(function(e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                let name = $('#edit_categories_name').val();

                $.ajax({
                    url: '/categories/update/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        categories_name: name
                    },
                    success: function(response) {
                        $('#CategoriesModal').modal('hide');
                        Swal.fire('Success!', 'Category updated successfully!', 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            });

            // Delete with SweetAlert
            $(document).on('click', '.deleteCategory', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This category will be deleted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/categories/delete/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'Category has been deleted.',
                                    'success');
                                table.ajax.reload();
                            },
                            error: function() {
                                Swal.fire('Error!', 'Unable to delete category.',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
