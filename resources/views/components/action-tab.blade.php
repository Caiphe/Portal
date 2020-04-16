@allowonce('action_tab')
<link href="{{ mix('/css/components/action_tab.css') }}" rel="stylesheet"/>
@endallowonce

@props(['title' => '', 'text', 'logo', 'status' => ''])
<a class="action-tab {{ $status }}">
    @isset($title)
        <strong>
            {{ $title }}
        </strong>
    @endisset
    &nbsp;{{ $text }}
    @svg('arrow-forward', '#FFFFFF')
    @isset($logo)
      @svg($logo, '#FFFFFF')
    @endisset
</a>
