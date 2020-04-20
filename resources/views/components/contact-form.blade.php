@allowonce('contact_form')
<link href="{{ mix('/css/components/contact-form.css') }}" rel="stylesheet"/>
@endallowonce

<div class="contact-form mb-4">
	<form action="{{ route('contact.send') }}" id="contact-form" method="POST">
		@csrf
		<h1 class="mb-4">{{ !empty($title) ? $title : 'Get in touch' }}</h1>
		@isset($slot)
		<p class="mb-2">{{$slot}}</p>
		@endisset
		<input type="text" name="first_name" placeholder="Enter first name" autocomplete="first_name">
		<input type="text" name="last_name" placeholder="Enter last name" autocomplete="last_name">
		<input type="email" name="email" placeholder="Enter email address" autocomplete="email">
		<textarea name="message" placeholder="Enter message" rows="4"></textarea>
		<button>Send message</button>
	</form>
</div>
