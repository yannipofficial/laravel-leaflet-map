@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <button id="createNew" class="btn btn-success m-2" onClick="showModal(null,0);">Create</button>

        <div class="container">
            <table id="table" data-toggle="table" data-click-to-select="true" data-ajax="ajaxRequest" data-search="true"
                data-side-pagination="server" data-unique-id="id" data-pagination="true">
                <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="id">ID</th>
                        <th data-field="userID">UserID</th>
                        <th data-field="likes">Likes</th>
                        <th data-field="cat">Category</th>
                        <th data-field="name">Name</th>
                        <th data-field="description">Description</th>
                        <th data-field="lat">Latitude</th>
                        <th data-field="long">Longitude</th>
                        <th data-formatter="TableActions">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="mdlLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mdlLabel">Create a new place</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="userIDInput">UserID</label>
                                <input type="text" class="form-control" id="userIDInput">
                            </div>
                            <div class="form-group">
                                <label for="nameInput">Name</label>
                                <input type="text" class="form-control" id="nameInput">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="lngInput">Longitude</label>
                                        <input type="text" class="form-control" id="lngInput">
                                    </div>
                                    <div class="col">
                                        <label for="latInput">Latitude</label>
                                        <input type="text" class="form-control" id="latInput">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="catInput">Category ID</label>
                                <input type="text" class="form-control" id="catInput">
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="descriptionInput">Description</label>
                                <textarea class="form-control" id="descriptionInput" rows="3"></textarea>
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
                        <h1 class="modal-title fs-5" id="mdlLabelDel">Delete place?</h1>
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
                    updateReq(modifiedID);
                })
                document.getElementById("deleteBtn").addEventListener("click", function() {
                    deleteReq(modifiedID);
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
                    document.getElementById('nameInput').value = row['name'];
                    document.getElementById('userIDInput').value = row['userID'];
                    document.getElementById('lngInput').value = row['long'];
                    document.getElementById('latInput').value = row['lat'];
                    document.getElementById('catInput').value = row['cat'];
                    document.getElementById('descriptionInput').value = row['description'];
                    document.getElementById('mdlLabel').innerText = "Modify Place";
                    modifiedID = row['id'];
                } else {
                    $('#Modal').modal('show');
                    document.getElementById('nameInput').value = "";
                    document.getElementById('userIDInput').value = "";
                    document.getElementById('lngInput').value = "";
                    document.getElementById('latInput').value = "";
                    document.getElementById('catInput').value = "";
                    document.getElementById('descriptionInput').value = "";
                    document.getElementById('mdlLabel').innerText = "Create Place";
                    modifiedID = null;
                }

            }

            function deleteModal(id) {

                $('#DelModal').modal('show');
                row = $('#table').bootstrapTable('getRowByUniqueId', id);
                modifiedID = row['id'];

            }

            function deleteReq(id) {
                $.ajax({
                    type: "DELETE",
                    url: '/admin/place/' + id,
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }

            function updateReq(id) {
                const nameInput = document.getElementById('nameInput').value;
                const lngInput = document.getElementById('lngInput').value;
                const latInput = document.getElementById('latInput').value;
                const catInput = document.getElementById('catInput').value;
                const userIDInput = document.getElementById('userIDInput').value;
                const descriptionInput = document.getElementById('descriptionInput').value;
                $.ajax({
                    type: "PUT",
                    url: '/admin/place/' + id,
                    data: {
                        name: nameInput,
                        userid: userIDInput,
                        longitude: lngInput,
                        latitude: latInput,
                        category: catInput,
                        description: descriptionInput
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }

            function ajaxRequest(params) {
                var url = '/admin/orderplaces';
                $.get(url + '?' + $.param(params.data) + '&page=' + (params.data['offset'] / params.data['limit'] + 1)).then(
                    function(res) {
                        params.success(res);
                        console.log(params.data['offset'] / params.data['limit'] + 1);
                    });
            }
        </script>
    </div>
@endsection
