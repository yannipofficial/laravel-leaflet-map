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
                        <th data-field="name">Name</th>
                        <th data-field="color" data-cell-style="cellStyle">
                            Color
                        </th>
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
                        <h1 class="modal-title fs-5" id="mdlLabel">Create a new category</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="CatNameInput">Category's Name</label>
                                <input type="text" class="form-control" id="CatNameInput">
                            </div>
                            <div class="form-group">
                                <label for="CatColorInput">Color</label>
                                <input type="color" class="form-control form-control-color" id="CatColorInput"
                                    value="#ffffff" title="Choose your color">
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
                        <h1 class="modal-title fs-5" id="mdlLabelDel">Delete category?</h1>
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
                    updateCats(modifiedID);
                })
                document.getElementById("deleteBtn").addEventListener("click", function() {
                    deleteCat(modifiedID);
                })
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function cellStyle(value, row, index) {
                return {
                    css: {
                        color: "#" + value,
                    },
                };
            }

            function ajaxRequest(params) {
                var url = "/admin/ordercategories";
                $.get(
                    url +
                    "?" +
                    $.param(params.data) +
                    "&page=" +
                    (params.data["offset"] / params.data["limit"] + 1)
                ).then(function(res) {
                    params.success(res);
                });
            }

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
                    document.getElementById('CatNameInput').value = row['name'];
                    document.getElementById('CatColorInput').value = "#" + row['color'];
                    document.getElementById('mdlLabel').innerText = "Modify Category";
                    modifiedID = row['id'];
                } else {
                    $('#Modal').modal('show');
                    document.getElementById('CatNameInput').value = "";
                    document.getElementById('CatColorInput').value = "#FFFFF";
                    document.getElementById('mdlLabel').innerText = "Create new Category";
                    modifiedID = null;
                }

            }

            function deleteModal(id) {

                $('#DelModal').modal('show');
                row = $('#table').bootstrapTable('getRowByUniqueId', id);
                modifiedID = row['id'];

            }

            function deleteCat(id) {
                $.ajax({
                    type: "DELETE",
                    url: '/admin/category/' + id,
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }

            function updateCats(id) {
                const nameInput = document.getElementById('CatNameInput').value;
                const colorInput = document.getElementById('CatColorInput').value;
                $.ajax({
                    type: "PUT",
                    url: '/admin/category/' + id,
                    data: {
                        name: nameInput,
                        color: colorInput.substring(1)
                    },
                    success: function(data) {
                        console.log(data);
                        location.reload();
                    }
                });
            }


            function getIdSelections() {
                return $.map($('#table').bootstrapTable('getSelections'), function(row) {
                    console.log(row.id);
                })
            }
        </script>
    </div>
@endsection
