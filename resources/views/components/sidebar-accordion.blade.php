@props(['list'])
@allowonce('sidebar_accordion')
<link href="/css/components/sidebar-accordion.css" rel="stylesheet"/>
@endallowonce

<div id="sidebar-accordion" {{ $attributes->merge(['class' => 'accordion-items']) }}>
    @foreach ($list as $accordion => $items) 

        <ul class="accordion-menu">
            <li class="accordion-item"><a href="#" data-toggle="accordion-item"> {{ $accordion }} <i class="icon-arrow"></i></a>
                <ul class="accordion-sub-items">
                @if(is_array($items)) 
                    @foreach($items as $sub_item) 
                        <li class="accordion-sub-item"><a href="{{ $sub_item['link'] }}">{{$sub_item['label']}}</a></li>
                    @endforeach
                @else  
                    <li class="accordion-sub-item"><a href="#">{{ $items }} </a></li>
                @endif 
                </ul>
            </li>
        </ul>
    @endforeach
</div>

@pushscript('sidebar_accordion')
<script src="/js/components/sidebar-accordion.js" defer></script>
@endpushscript