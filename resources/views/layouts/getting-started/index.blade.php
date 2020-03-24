 @push('styles')
    <link rel="stylesheet" href="/css/templates/getting-started/index.css">
@endpush
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield("title", '{"MTN":"Developer Portal"}')</title>
    <link rel="icon" href="/images/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ mix('/css/styles.css') }}">
    @stack("styles")
    <link rel="preload" href="/fonts/MTNBrighterSans-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Bold.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/MTNBrighterSans-Medium.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Regular.woff2" as="font" type="font/woff2">
    <link rel="preload" href="/fonts/Montserrat-Light.woff2" as="font" type="font/woff2">
</head>
<body>
    <x-header/>
        <div class="wrapper">
            <nav id="sidebar">
                @yield('sidebar')
                    <x-sidebar-accordion id="sidebar-accordion"  :list="[ 'Getting started' => [ [ 'label' => 'APN', 'link' => 'https://www.google.com/'], [ 'label' => 'APN', 'link' => 'https://www.google.com/'],[ 'label' => 'Devices','link' => 'https://www.google.com/'],[ 'label' => 'KYC','link' => 'https://www.google.com/']]]" />   
            </nav>
            <main id="main">
                @yield("content")
                <x-heading heading="Working with our products" tags="INTRODUCTION">
                </x-heading>

                <h2>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</h2>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata.</p>
                
                <div id="product-usage">
                    <h2>What you can do with our products</h2>
                    <x-card-link target="_blank" icon="apps-box" title="My Apps" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
                    <x-card-link target="_blank" icon="card-search" title="Browse products" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
                    <x-card-link target="_blank" icon="plus-circle-outline" title="Create an application" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
                    <x-card-link target="_blank" icon="check-all" title="Request approval" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
                    <x-card-link target="_blank" icon="code-braces" title="Responses and error codes" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
                    <x-card-link target="_blank" icon="help-network" title="FAQ" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
                    <x-card-link target="_blank" icon="lightbulb" title="Developer tips" linkUrl="http://www.google.com">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo.</x-card-link>
                </div>
            </main>
        </div>
    <x-footer/>
    <script src="/js/scripts.js"></script>
    @stack("scripts")
</body>
</html>