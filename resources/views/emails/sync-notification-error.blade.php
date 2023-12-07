@component('mail::message')

# Warning
- {{ $message }}

@component('mail::button', ['url' => route('admin.dashboard.index')])
Go to the dashboard
@endcomponent

@endcomponent
