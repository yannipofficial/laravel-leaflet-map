@extends('layouts.admin') 

@section('content')

<div class="container-fluid">
  <div class="container">
    <table
      id="table"
      data-toggle="table"
      data-click-to-select="true"
      data-ajax="ajaxRequest"
      data-search="true"
      data-side-pagination="server"
      data-pagination="true">
      <thead>
        <tr>
          <th data-field="state" data-checkbox="true"></th>
          <th data-field="id">ID</th>
          <th data-field="userID">UserID</th>
          <th data-field="action">Action</th>
          <th data-field="created_at">Date</th>
        </tr>
      </thead>
    </table>
  </div>
  
  <script>
    function ajaxRequest(params) {
      var url = '/admin/orderlogs';
      $.get(url + '?' + $.param(params.data) + '&page=' + (params.data['offset'] / params.data['limit'] + 1)).then(function (res) {
        params.success(res);
        console.log(params.data['offset'] / params.data['limit'] + 1);
      });
    }
  </script>
</div>
@endsection
