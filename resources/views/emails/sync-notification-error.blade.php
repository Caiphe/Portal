@component('mail::message')

# Warning
- {{ $message }}

@component('mail::button', ['url' => route('admin.dashboard.index')])
Proceed to the dashboard
@endcomponent

@endcomponent
