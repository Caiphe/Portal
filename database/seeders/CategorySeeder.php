<?php

namespace Database\Seeders;

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::insert([
            [
                "cid" => "advertising",
                "title" => "Advertising",
                "slug" => "advertising",
                "description" => "Reach customers with APIs engineered for mobile advertising. Get secure access to MTN customer data and unlock the power of personlized mobile ads",
                "theme" => "mixed"
            ],
            [
                "cid" => "agent",
                "title" => "Agent",
                "slug" => "agent",
                "description" => "Field Agent and KYC",
                "theme" => "standard"
            ],
            [
                "cid" => "analytics",
                "title" => "Analytics",
                "slug" => "analytics",
                "description" => "Our growing catalog of customer analytics APIs give you actionable intelligence to power a diverse range of solutions. Stay in touch to find out how you can build cutting-edge solutions with our set of customer insights. ",
                "theme" => "blue"
            ],
            [
                "cid" => "authentication",
                "title" => "Authentication",
                "slug" => "authentication",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "customer",
                "title" => "Customer",
                "slug" => "customer",
                "description" => "MTN Customer APIs provide all you need to create engaging user journeys right from within your applications.",
                "theme" => "pink"
            ],
            [
                "cid" => "customers",
                "title" => "Customers",
                "slug" => "customers",
                "description" => "Unused as of  11 Oct 2021-     use Customer instead",
                "theme" => "mixed"
            ],
            [
                "cid" => "fintech",
                "title" => "Fintech",
                "slug" => "fintech",
                "description" => "Learn the basics of MTN MoMo APIs and other Fintech related APIs, view available resources and join a community of developers building with MTN",
                "theme" => "dark"
            ],
            [
                "cid" => "messaging",
                "title" => "Messaging",
                "slug" => "messaging",
                "description" => "Send messages in a variety of formats - SMS, USSD, and more to come. Our collection of Messaging APIs provide all you need to create super engaging user experiences. ",
                "theme" => "standard"
            ],
            [
                "cid" => "onboarding",
                "title" => "Onboarding",
                "slug" => "onboarding",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "payment",
                "title" => "Payment",
                "slug" => "payment",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "regulatory",
                "title" => "Regulatory",
                "slug" => "regulatory",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "resource",
                "title" => "Resource",
                "slug" => "resource",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "security",
                "title" => "Security",
                "slug" => "security",
                "description" => "Preferred access method",
                "theme" => "standard"
            ],
            [
                "cid" => "services",
                "title" => "Services",
                "slug" => "services",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "sms",
                "title" => "Sms",
                "slug" => "sms",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "support",
                "title" => "Support",
                "slug" => "support",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "tadhack",
                "title" => "Tadhack",
                "slug" => "tadhack",
                "description" => "Credibly benchmark enabled intellectual work",
                "theme" => "mixed"
            ],
            [
                "cid" => "tickets",
                "title" => "Tickets",
                "slug" => "tickets",
                "description" => "Easily integrate to our ITSM system to automate your service managment interactions with MTN. Onlly available to select partners.",
                "theme" => "standard"
            ]
        ]);
    }
}
