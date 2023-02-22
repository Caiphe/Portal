<form class="custom-attributes-form-list" method="post" action="{{ route('app.update.attributes', $app) }}">
    @csrf
    @method('PUT')

    <input type="hidden" name="remove-check" class="remove-check" value=""/>
    @foreach ($app->custom_attributes as $key => $value)
        @if($key !== 'Notes' && $key !== 'ApprovedAt' && $value !== '') 
            <x-apps.custom-attribute :nameValue="$key" :valueValue="$value"></x-apps.custom-attribute>
        @endif
    @endforeach
    <div class="no-attribute">None defined</div>
</form>