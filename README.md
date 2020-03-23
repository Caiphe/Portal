# MTN Developer Portal

This is a re-write of the original MTN Developer Portal written using the Drupal 7 framework into using Laravel 7.

The aim of this build to to take the learnings from building the MTN Y'ello Web component based Wordpress site. We will create components that allow for quickly building up views as well as keeping the look and feel of the site consistant. We will also balance the need for creating components and creating css styles where the later is used for single element components therefore not bloating the developer portal.

Below is a living //TODO of what needs to be done to get parity with the current developer portal taking into account the learnings and best practices that we have picked up building the original Drupal 7 developer portal.

## Base setup
- Migrations and Models.
    - ~~Users.~~
    - ~~Products.~~
    - ~~Faqs.~~
    - ~~Documentation (general, not product.)~~
- ~~Master view layouts.~~

## Components
- Header.
    - ~~Header layout.~~
    - Header dropdown.
- General accordion (eg. for FAQ.)
- Search.
- Dialog / Alert (Neil).
- Product model view (Neil).
- ~~Key features (Xoliswa).~~
- ~~Footer.~~
- ~~Input (css.)~~
- ~~Heading with icon (css) (Takalani).~~
- ~~Carousel (WesDawg).~~
- ~~Tag (css) (Tebello).~~
- ~~Multiselect with tag (WesDawg).~~
- ~~Sidebar Accordion (Xoliswa).~~
- ~~Card product (Waseem).~~
- ~~Card link (Waseem).~~
- ~~Button. (css.)~~

## Helpers
- ~~SVG - pulls in an svg into a blade component~~
- ~~Blade components~~
    - ~~Styles (allowonce)~~
    - ~~Scripts (pushscripts)~~

## Layout
- ~~Full width.~~
- Left nav.

## Home page
- Build out view for home page.
- Route
    - Get: `/`

## User Access
- Registration
    - Routes
        - Get: `/register`
        - Post: `/register`
- Login
    - Routes
        - Get: `/login`
        - Post: `/login`
- Forgot Password
    - Routes
        - Get: `forgot-password`
        - Post: `forgot-password`

## Products
- Will contain three sections.
    - Overview
        - This will be added through the developer portal.
        - Will need to have a WYSIWYG editor.
    - Documentation
        - This will be added through the developer portal.
        - Will need to have a WYSIWYG editor.
    - Specification
        - This will be stored on Apigee and rendered on the product page.
        - A cached version will be stored for quick rendering.
        - The inital code to render the specification from an OpenApi yaml file can be found on the Drupal 7 version. This will need to be updated to follow Laravel's blade templating.
- Ability to add a product to a *yet to be made* app or to an already created app. This will require UI/UX to guide the user to the correct selection.
- Look into having helpful developer tools.
    - Model generation for statically typed languages.
    - Code boilerplate for dynamicallly typed languages.
- Routes (* please refer to `content` routes for Product Documentation and Product Overview admin.)
    - Get: `/products`
    - Get: `/products/{product:slug}`
    - Post: `/products/bucket` (adding products to a 'bucket' that the developer can choose from later on)
    - Post: `/products/app/{app:slug}`

## Documentation
- Will be a form of content type.
- Will need to have a WYSIWYG editor.
- Ability to tag for search to easily find.
- Routes (* please refer to `content` routes for General Documentation admin.)
    - Get: `/documentation`
    - Get: `/documentation/{documentation:slug}`

## Content
- Content is the CMS part of the developer portal.
- It is the content for:
    - Product Documentation.
    - Product Overview.
    - General Documents.
    - Insights (though Insights is not for this phase.)
- Content has a type associated with it. It can be anything but I suggest sticking to:
    - product_overview
    - product_documentation
    - general_documentation
    - insight
- Since products can have multiple content, there is a relationship between content and products.
- Routes
    - Post: `/content`
    - Put: `/content/{content}`
    - Delete: `/content/{content}`

## FAQ
- This will need fuzzy searching.
- Ability to like or dislike (so that we can tell if they need to be updated or if something is searched so much would point to what is being searched for needs to be addressed.)
- Form to fill in if the FAQs were no help.
- Route
    - Get: `/faq`
    - Post: `/faq`
    - Put: `/faq/{faq}`
    - Delete: `/faq/{faq}`

## Apps
- App Creation
    - When an app is being created, it would need to check if there are any products that have been assigned to a *yet to be made* app. If so, the products will be automatically assigned to this app.
    - Once all the information has be filled in (name, description, callback, products), the app with it's details will be sent to Apigee.
- App Viewing
    - Apps are not saved on the developer portal but are retrieved from Apigee and rendered for the user to see.
    - Apps will show the details associated to them, including their products which will be able to link through to their respective product page.
- App Editing
    - The developer portal will have the ability to edit and app and all the details that are associated to it.
- Routes
    - Get: `/my-apps`
    - Get: `/my-apps/{app:slug}`
    - Post: `/my-apps`
    - Put: `/my-apps/{app:slug}`
    - Delete: `/my-apps/{app:slug}`

## Search
- Fuzzy search will be on all pages and be able to talk to products, documentation and FAQs.
- Route
    - Get: `/search`

## Profile
- The profile will allow the user to be able to update their details and be able to join a team/organisation. These details will live on the developer portal but will need to sync with Apigee.
- Routes
    - Get: `/profile`
    - Put: `/profile`
    - Delete: `/profile`

## Contact us
- Route
    - Get: `contact-us`
    - Post: `contact-us` (form submission)

## Privacy policy
- Route
    - Get: `privacy-policy`

## Terms and conditions
- Route
    - Get: `terms-and-conditions`

## Note on Routes
- Initial version can be page reload. With future versions it would be great to load in the views that are needed.
- With any ajax actions (eg. deleting an FAQ) I would like to use [Sanctum](https://laravel.com/docs/7.x/sanctum) for security.