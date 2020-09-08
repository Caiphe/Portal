@csrf

<h2>Title</h2>
<input type="text" name="title" placeholder="Title" value="{{ $category->title ?? '' }}" autocomplete="off">

<h2>Theme</h2>
<select name="theme" autocomplete="off">
    <option @if(isset($category) && $category->theme === 'blue') selected @endif value="blue">Blue</option>
    <option @if(isset($category) && $category->theme === 'dark') selected @endif value="dark">Dark</option>
    <option @if(isset($category) && $category->theme === 'mixed') selected @endif value="mixed">Mixed</option>
    <option @if(isset($category) && $category->theme === 'pink') selected @endif value="pink">Pink</option>
    <option @if((isset($category) && $category->theme === 'standard') || (!isset($category) || $category->theme === null)) selected @endif value="standard">Standard</option>
</select>

<h2>Heading</h2>
<input type="text" name="heading-title" value="{{ $content['heading'][0]['title'] ?? '' }}" autocomplete="off">

<h2>Description</h2>
<input id="heading-body" type="hidden" name="heading-body" value="{{ $content['heading'][0]['body'] ?? '' }}">
<trix-editor input="heading-body"></trix-editor>

<h2>Benefits</h2>
<input type="hidden" name="benefits-title" value="Benefits">
<input id="benefits" type="hidden" name="benefits-body" value="{{ $content['benefits'][0]['body'] ?? 'Read the developer guides to get started on your integration. Find specific information about the different features of all MTN APIs. Access the community and support team to ensure your success' }}">
<trix-editor input="benefits"></trix-editor>

<h2>Developer centric</h2>
<input type="hidden" name="developer-centric-title" value="Developer centric">
<input id="developer-centric" type="hidden" name="developer-centric-body" value="{{ $content['developer-centric'][0]['body'] ?? 'All MTN APIs are built to ease the developer journey. Stay connected to find out ways to make your integration projects seamless with our growing catalog of SDKs, widgets and other develeper tools.' }}">
<trix-editor input="developer-centric"></trix-editor>

<h2>Bundles</h2>
<input type="hidden" name="bundles-title" value="Bundles">
<input id="bundles" type="hidden" name="bundles-body" value="{{ $content['bundles'][0]['body'] ?? '' }}">
<trix-editor input="bundles"></trix-editor>

<h2>Products</h2>
<input type="hidden" name="products-title" value="Products">
<input id="products" type="hidden" name="products-body" value="{{ $content['products'][0]['body'] ?? 'Ready to build? Browse our catalog of Mobile Advertising APIs to get started. And remember, our team of API experts are always available to provide valuable tips on how to accelerate your project.' }}">
<trix-editor input="products"></trix-editor>