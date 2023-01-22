@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <button id="createNew" class="btn btn-success m-2" onClick="showModal(null,0);">Create</button>

        <div class="container">
            <table id="table" data-toggle="table" data-click-to-select="true" data-ajax="ajaxRequest" data-search="true"
                data-unique-id="id" data-side-pagination="server" data-pagination="true">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="id">ID</th>
                        <th data-field="name">Name</th>
                        <th data-field="email">Email</th>
                        <th data-field="role">Role</th>
                        <th data-field="created_at">Created At</th>
                        <th data-formatter="TableActions">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="mdlLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdlLabel">Create a user</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="usernameInput">Username</label>
                                <input type="text" class="form-control" id="usernameInput">
                            </div>
                            <div class="form-group">
                                <label for="emailInput">Email</label>
                                <input type="text" class="form-control" id="emailInput">
                            </div>
                            <div class="form-group">
                                <label for="passwordInput">Password</label>
                                <input type="text" class="form-control" id="passwordInput">
                            </div>
                            <div class="form-group">
                                <label for="roleInput">Role</label>
                                <select class="form-select" id="roleInput">
                                    <option value="user" selected>user</option>
                                    <option value="admin">admin</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="postBtn" type="button" class="btn btn-primary">Post</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="DelModal" tabindex="-1" aria-labelledby="mdlLabelDel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdlLabelDel">Delete user?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete that?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="deleteBtn" type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var modifiedID = null;
            window.addEventListener('DOMContentLoaded', (event) => {
                document.getElementById("postBtn").addEventListener("click", function() {
                    updateUsers(modifiedID);
                })
                document.getElementById("deleteBtn").addEventListener("click", function() {
                    deleteUser(modifiedID);
                })
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function TableActions(value, row, index) {
                return [
                    '<a class="like" href="javascript:void(0)" title="Edit" data-id="' +
                    row.id +
                    '" onclick="showModal($(this).attr(\'data-id\'), 1)">',
                    '<i class="bi bi-pencil-square text-primary"></i>',
                    "</a> ",
                    '<a class="danger remove" href="javascript:void(0)" title="Remove" data-id="' +
                    row.id +
                    '" onclick="deleteModal($(this).attr(\'data-id\'))">',
                    '<i class="bi bi-trash text-danger"></i>',
                    "</a>",
                ].join("");
            }

            function showModal(id, type) {
                if (type == 1) {
                    $('#Modal').modal('show');
                    row = $('#table').bootstrapTable('getRowByUniqueId', id);
                    document.getElementById('usernameInput').value = row['name'];
                    document.getElementById('emailInput').value = row['email'];
                    document.getElementById('roleInput').value = row['role'];
                    document.getElementById('mdlLabel').innerText = "Modify User";
                    modifiedID = row['id'];
                } else {
                    $('#Modal').modal('show');
                    document.getElementById('usernameInput').value = "";
                    document.getElementById('emailInput').value = "";
                    document.getElementById('roleInput').value = "";
                    document.getElementById('mdlLabel').innerText = "Create User";
                    modifiedID = null;
                }

            }

            function deleteModal(id) {

                $('#DelModal').modal('show');
                row = $('#table').bootstrapTable('getRowByUniqueId', id);
                modifiedID = row['id'];

            }

            function ajaxRequest(params) {
                var url = '/admin/orderusers';
                $.get(url + '?' + $.param(params.data) + '&page=' + (params.data['offset'] / params.data['limit'] + 1)).then(
                    function(res) {
                        params.success(res);
                        console.log(params.data['offset'] / params.data['limit'] + 1);
                    });
            }

            function deleteUser(id) {
                $.ajax({
                    type: "DELETE",
                    url: '/admin/user/' + id,
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }

            function updateUsers(id) {
                const usernameInput = document.getElementById('usernameInput').value;
                const emailInput = document.getElementById('emailInput').value;
                const roleInput = document.getElementById('roleInput').value;
                const passwordInput = document.getElementById('passwordInput').value;
                $.ajax({
                    type: "PUT",
                    url: '/admin/user/' + id,
                    data: {
                        name: usernameInput,
                        email: emailInput,
                        password: passwordInput,
                        role: roleInput
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }
        </script>
    </div>
@endsection
