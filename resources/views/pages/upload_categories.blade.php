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


    <script>
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
    </script>
@endsection
