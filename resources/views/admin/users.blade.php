@extends('admin.template')
@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3 py-3">
            <h3>Data Users</h3>
            <header class="justify-content-end">
                <button class="btn btn-primary" id="btnAdd">Tambah User</button>
            </header>
        </div>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered" id="tabel_user">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="userForm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @csrf
        <input type="hidden" id="id" name="id">
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="form-group mb-3">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group mb-3">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address" required></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control">
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function(){
        $('#tabel_user').DataTable({
            ajax: {
                url: '/api/users-list',
                dataSrc: 'data',
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + API_TOKEN
                }
            },
            columns: [
                { data: null, 
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Menampilkan nomor urut
                }},
                { data: 'name' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'address' },
                { data: 'role' },
                { data: null, render: function (data, type, row) {
                    return `
                            <button class="btn btn-sm btn-warning btn-edit" data-id="${row.id}">Edit</button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}">Delete</button>
                        </form>
                    `;
                }}
             ],
             initComplete:function(settings, json){
                if (json.message) {
                    toastr.success(json.message);
                }
             },
             error: function(xhr, thrown, error) {
                toastr.error('Gagal memuat data users.');
                }
        });

        $('#btnAdd').click(function(){
            $('#userForm')[0].reset();
            $('#id').val('');
            $('#userModalLabel').text('Tambah User');
            $('#userModal').modal('show');
        })

        $('#userForm').submit(function(e){
            e.preventDefault();
            const id = $('#id').val();
            const url = id ? `/api/user/${id}` : '/api/user';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                headers: {
                    'Authorization': 'Bearer ' + API_TOKEN
                },
                success: function(response) {
                    $('#userModal').modal('hide');
                    $('#tabel_user').DataTable().ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    toastr.error('Gagal menyimpan data user.');
                }
            });
        });

        //edit
        $('#tabel_user').on('click', '.btn-edit', function(){
            const id = $(this).data('id');
            $.ajax({
                url: `/api/user/${id}`,
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + API_TOKEN
                },
                success: function(response) {
                    const user = response.data;
                    $('#id').val(user.id);
                    $('#name').val(user.name);
                    $('#password').val('');
                    $('#email').val(user.email);
                    $('#phone').val(user.phone);
                    $('#address').val(user.address);
                    $('#role').val(user.role);
                    $('#userModalLabel').text('Edit User');
                    $('#userModal').modal('show');
                },
                error: function(xhr, status, error) {
                    toastr.error('Gagal mengambil data user.');
                }
            });
        });

         //delete
         $('#tabel_user').on('click', '.btn-delete', function(){
            const id = $(this).data('id');
            if (!confirm('Apakah Anda yakin ingin menghapus user ini?')) return;
                $.ajax({
                    url: `/api/user/${id}`,
                    method: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + API_TOKEN
                    },
                    success: function(response) {
                        $('#tabel_user').DataTable().ajax.reload();
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Gagal menghapus data user.');
                    }
                });
        });
    });
</script>
@endsection