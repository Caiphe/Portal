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
            ['code' => 'af', 'name' => 'Afghanistan', 'iso' => 'AFG'],
            ['code' => 'al', 'name' => 'Albania', 'iso' => 'ALB'],
            ['code' => 'ao', 'name' => 'Angola', 'iso' => 'AGO'],
            ['code' => 'bf', 'name' => 'Burkina Faso', 'iso' => 'BFA'],
            ['code' => 'bi', 'name' => 'Burundi', 'iso' => 'BDI'],
            ['code' => 'bj', 'name' => 'Benin', 'iso' => 'BEN'],
            ['code' => 'bw', 'name' => 'Botswana', 'iso' => 'BWA'],
            ['code' => 'cd', 'name' => 'Congo (the Democratic Republic of the)', 'iso' => 'COD'],
            ['code' => 'cf', 'name' => 'Central African Republic (the)', 'iso' => 'CAF'],
            ['code' => 'cg', 'name' => 'Congo (the)', 'iso' => 'COG'],
            ['code' => 'ci', 'name' => "Côte d'Ivoire", 'iso' => 'CIV'],
            ['code' => 'cm', 'name' => 'Cameroon', 'iso' => 'CMR'],
            ['code' => 'cv', 'name' => 'Cabo Verde', 'iso' => 'CPV'],
            ['code' => 'dj', 'name' => 'Djibouti', 'iso' => 'DJI'],
            ['code' => 'do', 'name' => 'The Dominican Republic', 'iso' => 'DOM'],
            ['code' => 'dz', 'name' => 'Algeria', 'iso' => 'DZA'],
            ['code' => 'eg', 'name' => 'Egypt', 'iso' => 'EGY'],
            ['code' => 'eh', 'name' => 'Western Sahara', 'iso' => 'ESH'],
            ['code' => 'er', 'name' => 'Eritrea', 'iso' => 'ERI'],
            ['code' => 'et', 'name' => 'Ethiopia', 'iso' => 'ETH'],
            ['code' => 'ga', 'name' => 'Gabon', 'iso' => 'GAB'],
            ['code' => 'gh', 'name' => 'Ghana', 'iso' => 'GHA'],
            ['code' => 'gm', 'name' => 'Gambia (the)', 'iso' => 'GMB'],
            ['code' => 'gn', 'name' => 'Guinea', 'iso' => 'GIN'],
            ['code' => 'gq', 'name' => 'Equatorial Guinea', 'iso' => 'GNQ'],
            ['code' => 'gw', 'name' => 'Guinea-Bissau', 'iso' => 'GNB'],
            ['code' => 'il', 'name' => 'Israel', 'iso' => 'ISR'],
            ['code' => 'iq', 'name' => 'Iraq', 'iso' => 'IRQ'],
            ['code' => 'ir', 'name' => 'Iran (Islamic Republic of)', 'iso' => 'IRN'],
            ['code' => 'ke', 'name' => 'Kenya', 'iso' => 'KEN'],
            ['code' => 'km', 'name' => 'Comoros (the)', 'iso' => 'COM'],
            ['code' => 'lr', 'name' => 'Liberia', 'iso' => 'LBR'],
            ['code' => 'ls', 'name' => 'Lesotho', 'iso' => 'LSO'],
            ['code' => 'ly', 'name' => 'Libya', 'iso' => 'LBY'],
            ['code' => 'ma', 'name' => 'Morocco', 'iso' => 'MAR'],
            ['code' => 'mg', 'name' => 'Madagascar', 'iso' => 'MDG'],
            ['code' => 'mu', 'name' => 'Mauritius', 'iso' => 'MUS'],
            ['code' => 'mw', 'name' => 'Malawi', 'iso' => 'MWI'],
            ['code' => 'my', 'name' => 'Malaysia', 'iso' => 'MYS'],
            ['code' => 'mz', 'name' => 'Mozambique', 'iso' => 'MOZ'],
            ['code' => 'na', 'name' => 'Namibia', 'iso' => 'NAM'],
            ['code' => 'ne', 'name' => 'Niger (the)', 'iso' => 'NER'],
            ['code' => 'ng', 'name' => 'Nigeria', 'iso' => 'NGA'],
            ['code' => 'rw', 'name' => 'Rwanda', 'iso' => 'RWA'],
            ['code' => 'sb', 'name' => 'Solomon Islands', 'iso' => 'SLB'],
            ['code' => 'sc', 'name' => 'Seychelles', 'iso' => 'SYC'],
            ['code' => 'sd', 'name' => 'Sudan (the)', 'iso' => 'SDN'],
            ['code' => 'sl', 'name' => 'Sierra Leone', 'iso' => 'SLE'],
            ['code' => 'sn', 'name' => 'Senegal', 'iso' => 'SEN'],
            ['code' => 'so', 'name' => 'Somalia', 'iso' => 'SOM'],
            ['code' => 'ss', 'name' => 'South Sudan', 'iso' => 'SSD'],
            ['code' => 'st', 'name' => 'Sao Tome and Principe', 'iso' => 'STP'],
            ['code' => 'sy', 'name' => 'Syrian Arab Republic', 'iso' => 'SYR'],
            ['code' => 'sz', 'name' => 'Eswatini', 'iso' => 'SWZ'],
            ['code' => 'tg', 'name' => 'Togo', 'iso' => 'TGO'],
            ['code' => 'tn', 'name' => 'Tunisia', 'iso' => 'TUN'],
            ['code' => 'tz', 'name' => 'Tanzania, United Republic of', 'iso' => 'TZA'],
            ['code' => 'ug', 'name' => 'Uganda', 'iso' => 'UGA'],
            ['code' => 'uy', 'name' => 'Uruguay', 'iso' => 'URY'],
            ['code' => 'ye', 'name' => 'Yemen', 'iso' => 'YEM'],
            ['code' => 'za', 'name' => 'South Africa', 'iso' => 'ZAF'],
            ['code' => 'zm', 'name' => 'Zambia', 'iso' => 'ZMB'],
            ['code' => 'zw', 'name' => 'Zimbabwe', 'iso' => 'ZWE']
        ]);
    }
}
