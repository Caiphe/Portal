<form action="{{ $action }}">
    <input class="search" name="q" placeholder="Search term" autofocus>
</form>
@if(!empty($results))
<p class="tally"><strong>{{$total}}</strong> results for <strong>{{$searchTerm}}</strong></p>
@endif
<div class="search-results cols">
    @foreach($results as $result)
    <x-card-search :title="$result['title']" :link="$result['link']">{!!$result['description']!!}</x-card-search>
    @endforeach
</div>
@if($pages > 1)
<ul class="pagination">
    <li @class([
        'page-item',
        'disabled' => $page === 0
    ])><a class="page-link" href="?q={{$searchTerm}}&page={{$page}}" rel="prev" aria-label="« Previous">‹</a></li>
    @for ($i = 0; $i < $pages; $i++)
    <li @class([
        'page-item',
        'active' => $page === $i
    ])><a class="page-link" href="?q={{$searchTerm}}&page={{$i+1}}">{{$i+1}}</a></li>
    @endfor
    <li @class([
        'page-item',
        'disabled' => $page + 1 === $pages
    ])><a class="page-link" href="?q={{$searchTerm}}&page={{$page+2}}" rel="next" aria-label="Next »">›</a></li>
</ul>
@endif