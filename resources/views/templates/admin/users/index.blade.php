@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/users/index.css') }}">
@endpush

@section('title', 'Users')

@section('content')
    <h1>Users</h1>

    <div class="page-actions">
        <a href="{{ route('admin.user.create') }}" class="button primary">Create New User</a>
    </div>

    <x-admin.filter searchTitle="Product name">
        <label class="filter-item" for="status">
            User status
            <select name="status" class="users-status">
                <option value="">Select by status</option>
                <option value="verified" @if(request()->get('status') === 'verified') selected @endif>Verified</option>
                <option value="not_verified" @if(request()->get('status') === 'not_verified') selected @endif>Not verified</option>
            </select>
        </label>
    </x-admin.filter>

    <div id="table-data">
        @include('components.admin.list', [
            'collection' => $users,
            'fields' => ['First name' => 'first_name', 'Last name' => 'last_name', 'Email' => 'email', 'Member since' => 'created_at,date:d M Y', 'Role' => 'roles,implode:, |label', 'status' => 'status,splitToTag:,', 'apps' => 'apps_count'],
            'modelName' => 'user'
        ])
    </div>
@endsection
@push('scripts')
<script src="{{ mix('/js/templates/admin/index.js') }}" defer></script>
<script>
    ajaxifyOnPopState = updateFilters;
    function updateFilters(params) {
        document.getElementById('search-page').value = params['q'] || '';
        document.querySelector('.users-status').value = params['verified'] || '';
    }
</script>
@endpush
