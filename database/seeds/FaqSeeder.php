<?php

use App\Category;
use App\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            "Authentication",
            "Callback",
            "Error code",
            "Support",
            "Onboarding"
        ];

        $faqs = [
            "Is the API down?" => [
                "answer" => "If you need verify if the MTN API Platform is up and responsive, or perhaps down due to maintenance, then check out the status page",
                "category" => 4
            ],
            "Do you have sample or reference applications that could demonstrate some API calls for me?" => [
                "answer" => "Stay posted at our GitHub to see various reference helper applications and SDKs.",
                "category" => 5
            ],
            "I'm looking for a specific API functionality – how do I know if you offer it?" => [
                "answer" => "Take a look at our products page – it will let you know what APIs are available on a market-by-market basis.<br>If you have a really strong business case for a new API we'd love to hear about it! Send us a message through our <a href=\"/contact\">contact us</a> page.",
                "category" => 4
            ],
            "What system of authorization do you use for your APIs, and how do I get authorized to make calls?" => [
                "answer" => "We currently have two authorisation mechanisms: API Key, and OAuth. Most of the MTN APIs use API Key today to support legacy apps, but is swtiching over to OAuth. Each API products page will specify what authorisation mechanisms each API uses. <ul><li>API Key uses the x-api-key header, which you can get from the apps section on your profile, under <strong>Consumer Key</strong></li><li>We use a standard OAuth 2.0 scheme for authorization. To make calls, check out our OAuth page to get information on implementing a 2-legged and 3-legged OAuth flow.</li></ul>",
                "category" => 1
            ],
            "How do I move my application to production?" => [
                "answer" => "We're excited to see your creation! If you've done some testing and have a valid prototype or idea worked out, check out our <a href=\"/contact\">contact us</a> page and fill out the form. We'll engage your team and start vetting you for production access.",
                "category" => 5
            ],
            "I've forgotten my User ID and my Password. How do I recover them?" => [
                "answer" => "Head to the login page and hit the forgot User ID/Password.",
                "category" => 4
            ],
            "I have an idea for an API that would really enable my product. Can MTN help me?" => [
                "answer" => "Please let us know at our Contact Us page! We are always looking for new ways to expose APIs that enable the financial technology space and create new opportunities.",
                "category" => 4
            ],
            "What does it cost me?" => [
                "answer" => "There are no fees currently to access our sandbox.  If we allow you to move beyond the sandbox, at that time we can discuss next steps and pricing.",
                "category" => 5
            ],
            "What functionality is available in the sandbox?" => [
                "answer" => "Our functionality varies from region to region – though we provide simulated access to our points platform, customer profiles, accounts, and transactions across all regions. To see if your desired functionality is available in your product's region, be sure to check out our API catalog and documentation. Please be reminded that this a sandbox, which means a test environment, that only uses dummy data.",
                "category" => 5
            ],
            "What kind of data and access do I get in the portal?" => [
                "answer" => "The MTN Developer portal consists of: <ul><li>API Products Catalogue - listing the different APIs that can be used, the related documentation, and a way to \"Try it Out\", test out the APIs directly on the portal</li><li>User Profiles - allowing developers to Register, and create apps that use APIs, and the the related credentials/keys for those APIs</li><li>a ‘sandbox', which allows you to make API calls that are the same in form and function to our production environments. It contains mock test data so that you can prototype your application as if it were the real thing. We keep our public APIs sandboxed to protect our clients' data and validate products before moving them to production.</li></ul>",
                "category" => 5
            ],
            "Get started" => [
                "answer" => "Read the Welcome page, then head over to Things every developer should know.",
                "category" => 5
            ]
        ];

        foreach ($categories as $category) {
            Category::create([
                "title" => $category
            ]);
        }

        foreach ($faqs as $question => $detail) {
            Faq::create([
                "question" => $question,
                "answer" => $detail['answer'],
                "category_id" => $detail['category']
            ]);
        }
    }
}
