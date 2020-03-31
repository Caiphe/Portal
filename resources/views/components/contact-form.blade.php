@allowonce('contact_form')
<link href="/css/components/contact-form.css" rel="stylesheet"/>
@endallowonce

<div class="contact-form mb-4">
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	@if ($message = Session::get('success'))
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
		</div>
	@endif
	<form action="{{url('contact/sendMail')}}" id="contact-form" method="POST">
		@csrf
		@method('post')
		<h1 class="mb-4">Need more help? Get in touch</h1>
		<input type="text" name="first_name" placeholder="Enter first name" autocomplete="first_name">
		<input type="text" name="last_name" placeholder="Enter last name" autocomplete="last_name">
		<input type="email" name="email" placeholder="Enter email address" autocomplete="email">
		<textarea name="message" placeholder="Enter message" rows="4"></textarea>
		<button>Send message</button>
	</form>
</div>