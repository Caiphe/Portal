@props(['id', 'list'])
@allowonce('sidebar_accordion')
<link href="/css/components/sidebar-accordion.css" rel="stylesheet"/>
@endallowonce

<div id="sidebar-accordion" {{ $attributes->merge(['class' => 'accordion-items']) }}>
    <ul id="{{ $id }}" class="accordion-menu">    
        @foreach ($list as $accordion => $items) 
            <li class="accordion-item"><a href="#" data-toggle="accordion-item"> {{ $accordion }} <i class="icon-arrow">@svg('chevron-right', '#000000')</i></a>
                <ul class="accordion-sub-items">
                @if(is_array($items)) 
                    @foreach($items as $sub_item) 
                        <li class="accordion-sub-item"><a href="{{ $sub_item['link'] }}">{{$sub_item['label']}}<i class="arrow-forward">@svg('arrow-forward', '#000000')</i></a></li>
                    @endforeach
                @else  
                    <li class="accordion-sub-item"><a href="#">{{ $items }} <i class="arrow-forward">@svg('arrow-forward', '#000000')</i></a></li>
                @endif 
                </ul>
            </li>
        @endforeach
    </ul>
</div>

@pushscript('sidebar_accordion')
<script src="/js/components/sidebar-accordion.js" defer></script>
@endpushscript