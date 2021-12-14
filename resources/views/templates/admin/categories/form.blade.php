@csrf

<div class="editor-field one-third">
    <h2>Basic info</h2>

    <label class="editor-field-label">
        <h3>Theme</h3>
        <select name="theme" autocomplete="off">
            <option @if(isset($category) && $category->theme === 'blue') selected @endif value="blue">Blue</option>
            <option @if(isset($category) && $category->theme === 'dark') selected @endif value="dark">Dark</option>
            <option @if(isset($category) && $category->theme === 'mixed') selected @endif value="mixed">Mixed</option>
            <option @if(isset($category) && $category->theme === 'pink') selected @endif value="pink">Pink</option>
            <option @if((isset($category) && $category->theme === 'standard') || (!isset($category) || $category->theme === null)) selected @endif value="standard">Standard</option>
        </select>
    </label>

    <label class="editor-field-label">
        <h3>Title</h3>
        <input type="text" class="long" name="title" placeholder="Title" value="{{ $category->title ?? old('title') }}" autocomplete="off">
    </label>

    <label class="editor-field-label">
        <h3>Heading</h3>
        <input type="text" class="long" name="heading-title" value="{{ $content['heading'][0]['title'] ?? old('heading-title') }}" autocomplete="off">
    </label>

    <button class="button outline blue save-button">Apply changes</button>
</div>

<div class="editor-field two-thirds">
    <h2>Content</h2>

    <label class="editor-field-label">
        <h3>Description</h3>
        <div class="editor" data-input="heading-body">{!! $content['heading'][0]['body'] ?? old('heading-body') !!}</div>
    </label>

    <label class="editor-field-label">
        <h3>Benefits</h3>
        <input type="hidden" name="benefits-title" value="Benefits">
        <div class="editor" data-input="benefits-body">{!! $content['benefits'][0]['body'] ?? old('benefits-body') ?: 'Read the developer guides to get started on your integration. Find specific information about the different features of all MTN APIs. Access the community and support team to ensure your success' !!}</div>
    </label>

    <label class="editor-field-label">
        <h3>Developer centric</h3>
        <input type="hidden" name="developer-centric-title" value="Developer centric">
        <div class="editor" data-input="developer-centric-body">{!! $content['developer-centric'][0]['body'] ?? old('developer-centric-body') ?: 'All MTN APIs are built to ease the developer journey. Stay connected to find out ways to make your integration projects seamless with our growing catalog of SDKs, widgets and other develeper tools.' !!}</div>
    </label>

    <label class="editor-field-label">
        <h3>Bundles</h3>
        <input type="hidden" name="bundles-title" value="Bundles">
        <div class="editor" data-input="bundles-body">{!! $content['bundles'][0]['body'] ?? old('bundles-body') !!}</div>
    </label>

    <label class="editor-field-label">
        <h3>Products</h3>
        <input type="hidden" name="products-title" value="Products">
        <div class="editor" data-input="products-body">{!! $content['products'][0]['body'] ?? old('products-body') ?: 'Ready to build? Browse our catalog of Mobile Advertising APIs to get started. And remember, our team of API experts are always available to provide valuable tips on how to accelerate your project.' !!}</div>
    </label>

    <button class="button outline blue save-button">Apply changes</button>
</div>