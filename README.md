# MTN Developer Portal

This is a re-write of the original MTN Developer Portal written using the Drupal 7 framework into using Laravel 7.

The aim of this build to to take the learnings from building the MTN Y'ello Web component based Wordpress site. We will create components that allow for quickly building up views as well as keeping the look and feel of the site consistant. We will also balance the need for creating components and creating css styles where the later is used for single element components therefore not bloating the developer portal.

Below is a living //TODO of what needs to be done to get parity with the current developer portal taking into account the learnings and best practices that we have picked up building the original Drupal 7 developer portal.

## Base setup
- Migrations and Models.
    - ~~Users~~.
    - Products.
    - Faqs.
    - Documentation (general, not product.)
- ~~Master view layouts.~~

## Components
- ~~Card product (Waseem).~~
- ~~Card link (Waseem).~~
- ~~Button. (css.)~~
- Header.
    - ~~Header layout~~
    - Header dropdown
- ~~Footer.~~
- ~~Input (css.)~~
- ~~Heading with icon (css) (Takalani).~~
- Carousel (WesDawg).
- ~~Tag (css) (Tebello).~~
- Multiselect with tag (WesDawg).
- Accordian (Xoliswa).
- Dialog / Alert (Neil).
- ~~Key features (Xoliswa).~~
- Product model view (Neil).

## Helpers
- ~~SVG - pulls in an svg into a blade component~~

## Layout
- ~~Full width.~~
- Left nav.

## Home page
- Build out view for home page.

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

## Documentation
- Will be a form of content type.
- Will need to have a WYSIWYG editor.
- Ability to tag for search to easily find.

## Support / FAQ
- This will need fuzzy searching.
- Ability to like or dislike (so that we can tell if they need to be updated or if something is searched so much would point to what is being searched for needs to be addressed.)
- Form to fill in if the FAQs were no help.

## Apps
- App Creation
    - When an app is being created, it would need to check if there are any products that have been assigned to a *yet to be made* app. If so, the products will be automatically assigned to this app.
    - Once all the information has be filled in (name, description, callback, products), the app with it's details will be sent to Apigee.
- App Viewing
    - Apps are not saved on the developer portal but are retrieved from Apigee and rendered for the user to see.
    - Apps will show the details associated to them, including their products which will be able to link through to their respective product page.
- App Editing
    - The developer portal will have the ability to edit and app and all the details that are associated to it.

## Search
- Fuzzy search will be on all pages and be able to talk to products, documentation and FAQs.

## Profile
- The profile will allow the user to be able to update their details and be able to join a team/organisation. These details will live on the developer portal but will need to sync with Apigee.