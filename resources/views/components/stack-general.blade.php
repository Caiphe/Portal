@props(['cards'])

<div {{ $attributes->merge(['class' => 'stack-general']) }}>
    @forelse($cards as $card)
    <div class="stack-card">
        <h3>{{ $card['type'] }}</h3>
        <h4>{{ $card['title'] }}</h4>
        {!! $card['body'] !!}
        <a href="/{{ $card['slug'] }}">Read more @svg('arrow-forward')</a>
    </div>
    @empty
    <div class="stack-card">
        <h3>Content needed</h3>
        <h4>Content needed</h4>
        <p>Content needed</p>
    </div>
    <div class="stack-card">
        <h3>Content needed</h3>
        <h4>Content needed</h4>
        <p>Content needed</p>
    </div>
    <div class="stack-card">
        <h3>Content needed</h3>
        <h4>Content needed</h4>
        <p>Content needed</p>
    </div>
    @endforelse
</div>