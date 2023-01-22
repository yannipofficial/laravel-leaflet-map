<!-- Sidebar-->
<div class="border-end bg-white" id="sidebar-wrapper">
  <div class="sidebar-heading border-bottom bg-light">Admin Panel</div>
  <div class="list-group list-group-flush">
    <a
      class="list-group-item list-group-item-action list-group-item-light p-3 {{ Route::is('admin') ? 'active' : '' }}"
      href="{{ route('admin') }}"
      ><span class="material-symbols-outlined align-middle"> dashboard </span>Dashboard</a
    >
    <a
      class="list-group-item list-group-item-action list-group-item-light p-3 {{ Route::is('admin.places') ? 'active' : '' }}"
      href="{{ route('admin.places') }}"
      ><span class="material-symbols-outlined align-middle"> location_on </span>Places</a
    >
    <a
      class="list-group-item list-group-item-action list-group-item-light p-3 {{ Route::is('admin.users') ? 'active' : '' }}"
      href="{{ route('admin.users') }}"
      ><span class="material-symbols-outlined align-middle"> person </span>Users</a
    >
    <a
      class="list-group-item list-group-item-action list-group-item-light p-3 {{ Route::is('admin.comments') ? 'active' : '' }}"
      href="{{ route('admin.comments') }}"
      ><span class="material-symbols-outlined align-middle"> forum </span>Comments</a
    >
    <a
      class="list-group-item list-group-item-action list-group-item-light p-3 {{ Route::is('admin.categories') ? 'active' : '' }}"
      href="{{ route('admin.categories') }}"
      ><span class="material-symbols-outlined align-middle"> category </span>Categories</a
    >
    <a
      class="list-group-item list-group-item-action list-group-item-light p-3 {{ Route::is('admin.logs') ? 'active' : '' }}"
      href="{{ route('admin.logs') }}"
      ><span class="material-symbols-outlined align-middle"> terminal </span>Logs</a
    >
  </div>
</div>
