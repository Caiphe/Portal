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
            ["name" => "Afghanistan", "code" => "af", 'iso' => 'AFG'],
            ["name" => "Albania", "code" => "al", 'iso' => 'ALB'],
            ["name" => "Algeria", "code" => "dz", 'iso' => 'AGO'],
            ["name" => "Angola", "code" => "ao", 'iso' => 'BFA'],
            ["name" => "Benin", "code" => "bj", 'iso' => 'BDI'],
            ["name" => "Botswana", "code" => "bw", 'iso' => 'BEN'],
            ["name" => "Burkina Faso", "code" => "bf", 'iso' => 'BWA'],
            ["name" => "Burundi", "code" => "bi", 'iso' => 'COD'],
            ["name" => "Cabo Verde", "code" => "cv", 'iso' => 'CAF'],
            ["name" => "Cameroon", "code" => "cm", 'iso' => 'COG'],
            ["name" => "Central African Republic (the)", "code" => "cf", 'iso' => 'CIV'],
            ["name" => "Comoros (the)", "code" => "km", 'iso' => 'CMR'],
            [
                "name" => "Congo (the Democratic Republic of the)",
                "code" => "cd",
                'iso' => 'CPV'
            ],
            ["name" => "Congo (the)", "code" => "cg", 'iso' => 'DJI'],
            ["name" => "Côte d'Ivoire", "code" => "ci", 'iso' => 'DOM'],
            ["name" => "Djibouti", "code" => "dj", 'iso' => 'DZA'],
            ["name" => "The Dominican Republic", "code" => "do", 'iso' => 'EGY'],
            ["name" => "Egypt", "code" => "eg", 'iso' => 'ESH'],
            ["name" => "Equatorial Guinea", "code" => "gq", 'iso' => 'ERI'],
            ["name" => "Eritrea", "code" => "er", 'iso' => 'ETH'],
            ["name" => "Ethiopia", "code" => "et", 'iso' => 'GAB'],
            ["name" => "Gabon", "code" => "ga", 'iso' => 'GHA'],
            ["name" => "Gambia (the)", "code" => "gm", 'iso' => 'GMB'],
            ["name" => "Ghana", "code" => "gh", 'iso' => 'GIN'],
            ["name" => "Guinea", "code" => "gn", 'iso' => 'GNQ'],
            ["name" => "Guinea-Bissau", "code" => "gw", 'iso' => 'GNB'],
            ["name" => "Iran (Islamic Republic of)", "code" => "ir", 'iso' => 'ISR'],
            ["name" => "Iraq", "code" => "iq", 'iso' => 'IRQ'],
            ["name" => "Israel", "code" => "il", 'iso' => 'IRN'],
            ["name" => "Kenya", "code" => "ke", 'iso' => 'KEN'],
            ["name" => "Lesotho", "code" => "ls", 'iso' => 'COM'],
            ["name" => "Liberia", "code" => "lr", 'iso' => 'LBR'],
            ["name" => "Libya", "code" => "ly", 'iso' => 'LSO'],
            ["name" => "Madagascar", "code" => "mg", 'iso' => 'LBY'],
            ["name" => "Malawi", "code" => "mw", 'iso' => 'MAR'],
            ["name" => "Malaysia", "code" => "my", 'iso' => 'MDG'],
            ["name" => "Mauritius", "code" => "mu", 'iso' => 'MUS'],
            ["name" => "Morocco", "code" => "ma", 'iso' => 'MWI'],
            ["name" => "Mozambique", "code" => "mz", 'iso' => 'MYS'],
            ["name" => "Namibia", "code" => "na", 'iso' => 'MOZ'],
            ["name" => "Niger (the)", "code" => "ne", 'iso' => 'NAM'],
            ["name" => "Nigeria", "code" => "ng", 'iso' => 'NER'],
            ["name" => "Rwanda", "code" => "rw", 'iso' => 'NGA'],
            ["name" => "Sao Tome and Principe", "code" => "st", 'iso' => 'RWA'],
            ["name" => "Senegal", "code" => "sn", 'iso' => 'SLB'],
            ["name" => "Seychelles", "code" => "sc", 'iso' => 'SYC'],
            ["name" => "Sierra Leone", "code" => "sl", 'iso' => 'SDN'],
            ["name" => "Solomon Islands", "code" => "sb", 'iso' => 'SLE'],
            ["name" => "Somalia", "code" => "so", 'iso' => 'SEN'],
            ["name" => "South Africa", "code" => "za", 'iso' => 'SOM'],
            ["name" => "South Sudan", "code" => "ss", 'iso' => 'SSD'],
            ["name" => "Sudan (the)", "code" => "sd", 'iso' => 'STP'],
            ["name" => "Syrian Arab Republic", "code" => "sy", 'iso' => 'SYR'],
            ["name" => "Eswatini", "code" => "sz", 'iso' => 'SWZ'],
            ["name" => "Tanzania, United Republic of", "code" => "tz", 'iso' => 'TGO'],
            ["name" => "Togo", "code" => "tg", 'iso' => 'TUN'],
            ["name" => "Tunisia", "code" => "tn", 'iso' => 'TZA'],
            ["name" => "Uganda", "code" => "ug", 'iso' => 'UGA'],
            ["name" => "Uruguay", "code" => "uy", 'iso' => 'URY'],
            ["name" => "Western Sahara", "code" => "eh", 'iso' => 'YEM'],
            ["name" => "Yemen", "code" => "ye", 'iso' => 'ZAF'],
            ["name" => "Zambia", "code" => "zm", 'iso' => 'ZMB'],
            ["name" => "Zimbabwe", "code" => "zw", 'iso' => 'ZWE']
        ]);
    }
}
