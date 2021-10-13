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
            var filterForm = document.getElementById('users-search-form');

            if(filterForm.requestSubmit !== undefined) {
                filterForm.requestSubmit();
            }else{
                filterForm.submit();
            }
        }
        
        ajaxifyOnPopState = updateFilters;
        function updateFilters(params) {
            document.getElementById('search-page').value = params['q'] || '';
            document.querySelector('.users-status').value = params['verified'] || '';
            document.querySelector('.show-per-page').value = params['per-page'] || '10';
        }
    </script>
@endpush
