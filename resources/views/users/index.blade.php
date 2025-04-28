<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Sederhana</title>
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
<div class="container mt-5">
    <h2>Users Management</h2>
    <button class="btn btn-primary mb-3" id="btnTambahUser">Tambah User Baru</button>

    <table class="table table-bordered" id="usersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Role</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Photo</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

{{-- Modal --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="userForm" enctype="multipart/form-data">
            @csrf
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Tambah/Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="user_id" id="user_id">
            <div class="mb-3">
                <label>Role</label>
                <select name="role_id" class="form-control" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Photo</label>
                <input type="file" name="photo" class="form-control">
            </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
$(function() {
    var table = $('#usersTable').DataTable({
        ajax: "{{ route('users.fetch') }}",
        columns: [
            {data: 'id'},
            {data: 'role.name'},
            {data: 'name'},
            {data: 'phone'},
            {data: 'email'},
            {data: 'address'},
            {
                data: 'photo',
                render: function(data) {
                    if(data){
                        return `<img src="/storage/${data}" width="50" height="50"/>`;
                    }
                    return '';
                }
            },
            {
                data: 'id',
                render: function(data) {
                    return `
                        <button class="btn btn-success btn-sm editUser" data-id="${data}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteUser" data-id="${data}">Hapus</button>
                    `;
                }
            }
        ]
    });

    // Tambah Data
    $('#btnTambahUser').click(function(){
        $('#userForm').trigger('reset');
        $('#user_id').val('');
        $('#userModal').modal('show');
    });

    // Edit Data
    $(document).on('click', '.editUser', function(){
        var id = $(this).data('id');
        $.get("/users/" + id + "/edit", function(user){
            $('#userModal').modal('show');
            $('#user_id').val(user.id);
            $('[name="role_id"]').val(user.role_id);
            $('[name="name"]').val(user.name);
            $('[name="phone"]').val(user.phone);
            $('[name="email"]').val(user.email);
            $('[name="address"]').val(user.address);
        });
    });

    //Delete Data
    $(document).on('click', '.deleteUser', function(){
        if (confirm('Apakah kamu yakin ingin menghapus data ini?')) {
            var id = $(this).data('id');

            $.ajax({
                url: `/users/${id}/delete`,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        $('#usersTable').DataTable().ajax.reload();
                        alert(res.message);
                    }
                },
                error: function(err) {
                    alert('Gagal menghapus data.');
                    console.log(err.responseJSON);
                }
            });
        }
    });

    // Submit
    $('#userForm').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        var userId = $('#user_id').val();
        var url = "";

        if (userId) {
            url = `/users/${userId}/update`;
        } else {
            url = "{{ route('users.store') }}";
        }

        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(res){
                if(res.success){
                    $('#userModal').modal('hide');
                    $('#usersTable').DataTable().ajax.reload();
                    alert(res.message);
                }
            },
            error: function(err){
                if(err.responseJSON && err.responseJSON.errors){
                    let errors = err.responseJSON.errors;
                    let errorMessages = '';

                    $.each(errors, function(key, value){
                        errorMessages += value[0] + '\n';
                    });

                    alert('Error:\n' + errorMessages);
                } else {
                    alert('Terjadi kesalahan.');
                }
                console.log(err.responseJSON);
            }
        });
    });
});

</script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
</body>
</html>
