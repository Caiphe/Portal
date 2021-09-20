@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ mix('/css/templates/admin/index.css') }}">
@endpush

@section('title', 'Users')

@section('page-info')
    <a href="{{ route('admin.user.create') }}" class="button dark outline">Create New User</a>
@endsection

@section('content')
    <div class="user-table-list">
        <x-admin.users-table
            :collection="$users"
            :fields="['first_name', 'last_name', 'email', 'member_since', 'role', 'status', 'apps']"
            :order="$order"
            :defaultSortQuery="$defaultSortQuery">
            </x-admin.users-table>
    </div>
@endsection
@push('scripts')
    <script>
        document.querySelector('.users-status').addEventListener('change', handleChangedFilter);
        document.querySelector('.show-per-page').addEventListener('change', handleChangedFilter);

        function handleChangedFilter() {
            document.getElementById('users-search-form').dispatchEvent(new Event('submit'));
        }
    </script>
@endpush
