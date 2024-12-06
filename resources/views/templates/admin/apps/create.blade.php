@extends('layouts.admin')

@section('title', 'Create app')

@push('styles')
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/edit.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/templates/admin/dashboard/create-app.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
@endpush

@section('content')

    <x-twofa-warning class="tall"></x-twofa-warning>

    <div class="content">
        <h2 class="main-heading">Create a new application</h2>
        <form id="create-app-form"
            class="create-app-form app-form"
            action="{{ route('app.store') }}"
            data-redirect="{{ route('app.index') }}"
            >

            @csrf

            <div class="app-owner-container">
                <h3>App Owner </h3>
                <lablel class="gray-text">User*</lablel>
                <div class="owners-list-container">
                    <div class="wrapper-content">
                        <div class="search-input">
                            <input type="text" class="search-field" name="app-owner" value="{{ $chosenUser->email ?? '' }}" placeholder="Search for email address..." autocomplete="off">
                            <div class="autocom-box">
                                <!-- here list are inserted from javascript -->
                            </div>
                        </div>
                    </div>

                    <div class="owner-details">
                        <div class="each-block application-created-for">The application will be created for</div>
                        <div class="each-block owner-avatar-container">
                            <div class="owner-avatar"></div>
                        </div>
                        <div class="each-block owner-name creator-email">No creator assigned</div>
                        <button type="button" class="linkBtn each-block" id="assign-to-me">Assign to me</button>
                        <button type="button" class="linkBtn each-block remove-thumbnail" id="remove-assignee">Remove assignee</button>
                    </div>

                </div>
                <div id="app_owner_error" class="error"></div>
            </div>

            {{-- Custom attributes --}}
            <div class="custom-attribute-container">
                <div class="custom-attribute-list-container">
                    <h5 class="custom-attribute-heading">Custom attributes</h5>
    
                    <span class="no-attribute"></span>
    
                    <div class="attributes-heading">
                        <h4 class="name-heading">Attribute name</h4>
                        <h4 class="value-heading">Value</h4>
                    </div>
    
                    <div class="custom-attributes-list" id="custom-attributes-list"></div>
    
                </div>
    
                <div class="custom-attributes-form" action="">
                    <div class="each-field">
                        <label for="name">Attribute name</label>
                        <input type="text" value="" name="attribute_name" id="attribute-name" class="attribute-field attribute-name" placeholder="New attribute name"/>
                    </div>
                    <div class="each-field">
                        <label for="value">Value</label>
                        <input type="text" value="" name="attribute_value" id="attribute-value" class="attribute-field attribute-value" placeholder="New value"/>
                    </div>
                    <button type="button" class="button btn dark outline add-attribute" id="add-attribute">Add</button>
                </div>
                <div class="attribute-error" id="attribute-error">Attribute name and value required</div>
    
            </div>
 
            {{-- Custom attributes ends --}}
            <div class="form-fields">
                @include('templates.apps.partials.create-form')
            </div>

            <div class="create-form-actions">
                <div class="first">
                    <p><strong>Create a new application</strong></p>
                    <p>Complete all required details before completion.</p>
                </div>
                <div class="second">
                    <div class="form-actions">
                        <button type="button"
                                class="button dark outline back"
                                id="cancel"
                                data-back-url="{{ route('app.index') }}"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="button primary"
                            id="complete"
                        >Complete</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
       
    <template id="custom-attribute" hidden>
        <x-apps.custom-attribute></x-apps.custom-attribute>
    </template>
@endsection

@push('scripts')

<script>
    function adminAppsCreateLookup(key) {
        return {
            'userEmails': @json($userEmails),
            'userProfiles': @json($userProfiles),
            'appCreatorEmail': '{{ $appCreatorEmail }}',
            'appStoreUrl': '{{ route('app.store') }}',
            'csrfToken': '{{ csrf_token() }}'
        }[key] ?? null;
    }
</script>

<script src="{{ mix('/js/components/app-name-check.js') }}" defer></script>
<script src="{{ mix('/js/components/app-validate-fields.js') }}" defer></script>
<script src="{{ mix('/js/templates/admin/apps/store.js') }}" defer></script>
@endpush
