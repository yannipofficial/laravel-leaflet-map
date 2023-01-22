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
                        <th data-field="name">Username</th>
                        <th data-field="userID">UserID</th>
                        <th data-field="commentBody">Comment</th>
                        <th data-field="placeID">Place ID</th>
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
                        <h1 class="modal-title fs-5" id="mdlLabel">Create a new comment</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="userIDInput">User ID</label>
                                <input type="text" class="form-control" id="userIDInput">
                            </div>
                            <div class="form-group">
                                <label for="placeIDInput">Place ID</label>
                                <input type="text" class="form-control" id="placeIDInput">
                            </div>
                            <div class="form-group">
                                <label for="commentInput">Comment:</label>
                                <textarea class="form-control" id="commentInput" rows="3"></textarea>
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
                        <h1 class="modal-title fs-5" id="mdlLabelDel">Delete comment?</h1>
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
                    document.getElementById('userIDInput').value = row['userID'];
                    document.getElementById('placeIDInput').value = row['placeID'];
                    document.getElementById('commentInput').value = row['commentBody'];
                    document.getElementById('mdlLabel').innerText = "Modify Comment";
                    modifiedID = row['id'];
                } else {
                    $('#Modal').modal('show');
                    document.getElementById('userIDInput').value = "";
                    document.getElementById('placeIDInput').value = "";
                    document.getElementById('commentInput').value = "";
                    document.getElementById('mdlLabel').innerText = "Create Comment";
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
                    url: '/admin/comment/' + id,
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }

            function updateReq(id) {
                const userIDInput = document.getElementById('userIDInput').value;
                const placeIDInput = document.getElementById('placeIDInput').value;
                const commentInput = document.getElementById('commentInput').value;
                $.ajax({
                    type: "PUT",
                    url: '/admin/comment/' + id,
                    data: {
                        userid: userIDInput,
                        placeid: placeIDInput,
                        comment: commentInput
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }

            function ajaxRequest(params) {
                var url = '/admin/ordercomments';
                $.get(url + '?' + $.param(params.data) + '&page=' + (params.data['offset'] / params.data['limit'] + 1)).then(
                    function(res) {
                        params.success(res);
                    });
            }
        </script>
    </div>
@endsection
