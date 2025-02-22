@extends('structure.main')

@section('content')
    <div class="breadcrumb-wrapper">
        <h2 class="page-title">List Of All User's</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href=/home">Home</a></li>

            <li class="breadcrumb-item active">User List</li>
        </ol>
    </div>
    <div class="card">
        <div class="card-header ">
            <h3 class="mb-0">List of Users</h3>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="nameFilter" class="form-control" placeholder="Search by Name">
                </div>
                <div class="col-md-4">
                    <input type="email" id="emailFilter" class="form-control" placeholder="Search by Email">
                </div>
            </div>

            <table id="usersTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>SR No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
    </div>



    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        @csrf
                        <input type="hidden" id="editUserId">

                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" required>
                        </div>

                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>

                        <div class="mb-3">
                            <label for="editGender" class="form-label">Gender</label>
                            <select class="form-control" id="editGender">
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                                <option value="3">Prefer not to say</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editMobile" class="form-label">Mobile</label>
                            <input type="text" class="form-control" id="editMobile" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        let usersTable;
        $(document).ready(function() {
            usersTable = $('#usersTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "/get-users",
                    type: "GET",
                    data: function(d) {
                        d.name = $('#nameFilter').val();
                        d.email = $('#emailFilter').val();
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: "name"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: "gender",
                        render: function(data) {
                            return data == "1" ? "Male" : data == "2" ? "Female" :
                                "Prefer not to say";
                        }
                    },
                    {
                        data: "mobile"
                    },
                    {
                        data: "id",
                        render: function(data, type, row) {
                            return `
                    <button class="btn btn-sm btn-primary editUser" data-id="${data}" data-name="${row.name}" data-email="${row.email}" data-gender="${row.gender}" data-mobile="${row.mobile}"><i class="ti ti-edit-circle"></i></button>
                    <button class="btn btn-sm btn-danger deleteUser" data-id="${data}"><i class="ti ti-trash"></i></button>
                `;
                        }
                    }
                ]
            });


            $('#nameFilter, #emailFilter').on('keyup', function() {
                usersTable.ajax.reload();
            });
        });

        $(document).on('click', '.deleteUser', function() {
            var userId = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/users/delete/" + userId,
                        type: "DELETE",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire("Deleted!", "User has been deleted.", "success");
                            usersTable.ajax.reload();
                        },
                        error: function() {
                            Swal.fire("Error!", "Failed to delete user.", "error");
                        }
                    });
                }
            });
        });

        $(document).on('click', '.editUser', function() {
            $('#editUserId').val($(this).data('id'));
            $('#editName').val($(this).data('name'));
            $('#editEmail').val($(this).data('email'));
            $('#editGender').val($(this).data('gender'));
            $('#editMobile').val($(this).data('mobile'));

            $('#editUserModal').modal('show');
        });

        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();

            var userId = $('#editUserId').val();
            var formData = {
                name: $('#editName').val(),
                email: $('#editEmail').val(),
                gender: $('#editGender').val(),
                mobile: $('#editMobile').val(),

            };

            $.ajax({
                url: "/users/update/" + userId,
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(response) {
                    Swal.fire("Updated!", "User has been updated successfully.", "success");
                    $('#editUserModal').modal('hide');
                    usersTable.ajax.reload();
                },
                error: function() {
                    Swal.fire("Error!", "Failed to update user.", "error");
                }
            });
        });
    </script>
@endsection
