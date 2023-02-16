<div class="custom-attribute-data">
    <h4 class="custom-attribute-data-heading">Custom attributes</h4>
    <div class="list-custom-attributes">
        @forelse ($app->attributes as $key => $value)
        @if($key !== 'Notes' && $key !== 'ApprovedAt' && $value !== '') 
            <div class="attribute-display">
                <span class="attr-name bold"> {!! $key !!} : </span>
                <span class="attr-value">{!! $value !!}</span>
            </div>
        @endif
        @empty
            <div class="no-custom-attribute">None defined</div>
        @endforelse
    </div>
</div>