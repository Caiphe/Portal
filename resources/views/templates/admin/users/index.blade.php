@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
<link rel="stylesheet" href="{{ mix('/css/templates/admin/users/index.css') }}">
@endpush

@section('title', 'Users')

@section('content')
    <h1>Users</h1>

    <div class="page-actions">
        <a href="{{ route('admin.user.create') }}" class="button primary page-actions-create" aria-label="Create new user"></a>
    </div>

    <x-admin.filter searchTitle="User's name / email address">
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
            'fields' => ['First name' => 'first_name', 'Last name' => 'last_name|addClass:not-on-mobile', 'Email' => 'email', 'Member since' => 'created_at|date:d M Y|addClass:not-on-mobile', 'Role' => 'roles|implode:, >label|addClass:not-on-mobile', 'Status' => 'status|splitToTag:,|addClass:not-on-mobile', 'Apps' => 'apps_count|addClass:not-on-mobile'],
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
