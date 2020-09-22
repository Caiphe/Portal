@allowonce('action_tab')
<link href="{{ mix('/css/components/action_tab.css') }}" rel="stylesheet"/>
@endallowonce

@props(['link' => '', 'title' => '', 'text', 'logo', 'status' => ''])
<a class="action-tab {{ $status }}" href="{{ $link }}" target="_blank" rel="noopener noreferrer">
    @isset($title)
        <strong>
            {{ $title }}
        </strong>
    @endisset
    <div>&nbsp;{{ $text }}</div>
    @svg('arrow-forward', '#FFFFFF')
    @isset($logo)
      @svg($logo, '#FFFFFF')
    @endisset
</a>
