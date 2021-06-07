<?php

namespace Database\Seeders;

use App\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::insert([
            ["name" => "Afghanistan", "code" => "af"],
            ["name" => "Albania", "code" => "al"],
            ["name" => "Algeria", "code" => "dz"],
            ["name" => "Angola", "code" => "ao"],
            ["name" => "Benin", "code" => "bj"],
            ["name" => "Botswana", "code" => "bw"],
            ["name" => "Burkina Faso", "code" => "bf"],
            ["name" => "Burundi", "code" => "bi"],
            ["name" => "Cabo Verde", "code" => "cv"],
            ["name" => "Cameroon", "code" => "cm"],
            ["name" => "Central African Republic (the)", "code" => "cf"],
            ["name" => "Comoros (the)", "code" => "km"],
            [
                "name" => "Congo (the Democratic Republic of the)",
                "code" => "cd"
            ],
            ["name" => "Congo (the)", "code" => "cg"],
            ["name" => "Côte d'Ivoire", "code" => "ci"],
            ["name" => "Djibouti", "code" => "dj"],
            ["name" => "The Dominican Republic", "code" => "do"],
            ["name" => "Egypt", "code" => "eg"],
            ["name" => "Equatorial Guinea", "code" => "gq"],
            ["name" => "Eritrea", "code" => "er"],
            ["name" => "Ethiopia", "code" => "et"],
            ["name" => "Gabon", "code" => "ga"],
            ["name" => "Gambia (the)", "code" => "gm"],
            ["name" => "Ghana", "code" => "gh"],
            ["name" => "Guinea", "code" => "gn"],
            ["name" => "Guinea-Bissau", "code" => "gw"],
            ["name" => "Iran (Islamic Republic of)", "code" => "ir"],
            ["name" => "Iraq", "code" => "iq"],
            ["name" => "Israel", "code" => "il"],
            ["name" => "Kenya", "code" => "ke"],
            ["name" => "Lesotho", "code" => "ls"],
            ["name" => "Liberia", "code" => "lr"],
            ["name" => "Libya", "code" => "ly"],
            ["name" => "Madagascar", "code" => "mg"],
            ["name" => "Malawi", "code" => "mw"],
            ["name" => "Malaysia", "code" => "my"],
            ["name" => "Mauritius", "code" => "mu"],
            ["name" => "Morocco", "code" => "ma"],
            ["name" => "Mozambique", "code" => "mz"],
            ["name" => "Namibia", "code" => "na"],
            ["name" => "Niger (the)", "code" => "ne"],
            ["name" => "Nigeria", "code" => "ng"],
            ["name" => "Rwanda", "code" => "rw"],
            ["name" => "Sao Tome and Principe", "code" => "st"],
            ["name" => "Senegal", "code" => "sn"],
            ["name" => "Seychelles", "code" => "sc"],
            ["name" => "Sierra Leone", "code" => "sl"],
            ["name" => "Solomon Islands", "code" => "sb"],
            ["name" => "Somalia", "code" => "so"],
            ["name" => "South Africa", "code" => "za"],
            ["name" => "South Sudan", "code" => "ss"],
            ["name" => "Sudan (the)", "code" => "sd"],
            ["name" => "Syrian Arab Republic", "code" => "sy"],
            ["name" => "Eswatini", "code" => "sz"],
            ["name" => "Tanzania, United Republic of", "code" => "tz"],
            ["name" => "Togo", "code" => "tg"],
            ["name" => "Tunisia", "code" => "tn"],
            ["name" => "Uganda", "code" => "ug"],
            ["name" => "Uruguay", "code" => "uy"],
            ["name" => "Western Sahara", "code" => "eh"],
            ["name" => "Yemen", "code" => "ye"],
            ["name" => "Zambia", "code" => "zm"],
            ["name" => "Zimbabwe", "code" => "zw"]
        ]);
    }
}
