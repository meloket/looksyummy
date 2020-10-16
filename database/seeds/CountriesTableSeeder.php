<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('countries')->delete();
        
        \DB::table('countries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'sortname' => 'AF',
                'name' => 'Afghanistan',
            ),
            1 => 
            array (
                'id' => 2,
                'sortname' => 'AL',
                'name' => 'Albania',
            ),
            2 => 
            array (
                'id' => 3,
                'sortname' => 'DZ',
                'name' => 'Algeria',
            ),
            3 => 
            array (
                'id' => 4,
                'sortname' => 'AS',
                'name' => 'American Samoa',
            ),
            4 => 
            array (
                'id' => 5,
                'sortname' => 'AD',
                'name' => 'Andorra',
            ),
            5 => 
            array (
                'id' => 6,
                'sortname' => 'AO',
                'name' => 'Angola',
            ),
            6 => 
            array (
                'id' => 7,
                'sortname' => 'AI',
                'name' => 'Anguilla',
            ),
            7 => 
            array (
                'id' => 8,
                'sortname' => 'AQ',
                'name' => 'Antarctica',
            ),
            8 => 
            array (
                'id' => 9,
                'sortname' => 'AG',
                'name' => 'Antigua And Barbuda',
            ),
            9 => 
            array (
                'id' => 10,
                'sortname' => 'AR',
                'name' => 'Argentina',
            ),
            10 => 
            array (
                'id' => 11,
                'sortname' => 'AM',
                'name' => 'Armenia',
            ),
            11 => 
            array (
                'id' => 12,
                'sortname' => 'AW',
                'name' => 'Aruba',
            ),
            12 => 
            array (
                'id' => 13,
                'sortname' => 'AU',
                'name' => 'Australia',
            ),
            13 => 
            array (
                'id' => 14,
                'sortname' => 'AT',
                'name' => 'Austria',
            ),
            14 => 
            array (
                'id' => 15,
                'sortname' => 'AZ',
                'name' => 'Azerbaijan',
            ),
            15 => 
            array (
                'id' => 16,
                'sortname' => 'BS',
                'name' => 'Bahamas The',
            ),
            16 => 
            array (
                'id' => 17,
                'sortname' => 'BH',
                'name' => 'Bahrain',
            ),
            17 => 
            array (
                'id' => 18,
                'sortname' => 'BD',
                'name' => 'Bangladesh',
            ),
            18 => 
            array (
                'id' => 19,
                'sortname' => 'BB',
                'name' => 'Barbados',
            ),
            19 => 
            array (
                'id' => 20,
                'sortname' => 'BY',
                'name' => 'Belarus',
            ),
            20 => 
            array (
                'id' => 21,
                'sortname' => 'BE',
                'name' => 'Belgium',
            ),
            21 => 
            array (
                'id' => 22,
                'sortname' => 'BZ',
                'name' => 'Belize',
            ),
            22 => 
            array (
                'id' => 23,
                'sortname' => 'BJ',
                'name' => 'Benin',
            ),
            23 => 
            array (
                'id' => 24,
                'sortname' => 'BM',
                'name' => 'Bermuda',
            ),
            24 => 
            array (
                'id' => 25,
                'sortname' => 'BT',
                'name' => 'Bhutan',
            ),
            25 => 
            array (
                'id' => 26,
                'sortname' => 'BO',
                'name' => 'Bolivia',
            ),
            26 => 
            array (
                'id' => 27,
                'sortname' => 'BA',
                'name' => 'Bosnia and Herzegovina',
            ),
            27 => 
            array (
                'id' => 28,
                'sortname' => 'BW',
                'name' => 'Botswana',
            ),
            28 => 
            array (
                'id' => 29,
                'sortname' => 'BV',
                'name' => 'Bouvet Island',
            ),
            29 => 
            array (
                'id' => 30,
                'sortname' => 'BR',
                'name' => 'Brazil',
            ),
            30 => 
            array (
                'id' => 31,
                'sortname' => 'IO',
                'name' => 'British Indian Ocean Territory',
            ),
            31 => 
            array (
                'id' => 32,
                'sortname' => 'BN',
                'name' => 'Brunei',
            ),
            32 => 
            array (
                'id' => 33,
                'sortname' => 'BG',
                'name' => 'Bulgaria',
            ),
            33 => 
            array (
                'id' => 34,
                'sortname' => 'BF',
                'name' => 'Burkina Faso',
            ),
            34 => 
            array (
                'id' => 35,
                'sortname' => 'BI',
                'name' => 'Burundi',
            ),
            35 => 
            array (
                'id' => 36,
                'sortname' => 'KH',
                'name' => 'Cambodia',
            ),
            36 => 
            array (
                'id' => 37,
                'sortname' => 'CM',
                'name' => 'Cameroon',
            ),
            37 => 
            array (
                'id' => 38,
                'sortname' => 'CA',
                'name' => 'Canada',
            ),
            38 => 
            array (
                'id' => 39,
                'sortname' => 'CV',
                'name' => 'Cape Verde',
            ),
            39 => 
            array (
                'id' => 40,
                'sortname' => 'KY',
                'name' => 'Cayman Islands',
            ),
            40 => 
            array (
                'id' => 41,
                'sortname' => 'CF',
                'name' => 'Central African Republic',
            ),
            41 => 
            array (
                'id' => 42,
                'sortname' => 'TD',
                'name' => 'Chad',
            ),
            42 => 
            array (
                'id' => 43,
                'sortname' => 'CL',
                'name' => 'Chile',
            ),
            43 => 
            array (
                'id' => 44,
                'sortname' => 'CN',
                'name' => 'China',
            ),
            44 => 
            array (
                'id' => 45,
                'sortname' => 'CX',
                'name' => 'Christmas Island',
            ),
            45 => 
            array (
                'id' => 46,
                'sortname' => 'CC',
            'name' => 'Cocos (Keeling) Islands',
            ),
            46 => 
            array (
                'id' => 47,
                'sortname' => 'CO',
                'name' => 'Colombia',
            ),
            47 => 
            array (
                'id' => 48,
                'sortname' => 'KM',
                'name' => 'Comoros',
            ),
            48 => 
            array (
                'id' => 49,
                'sortname' => 'CG',
                'name' => 'Congo',
            ),
            49 => 
            array (
                'id' => 50,
                'sortname' => 'CD',
                'name' => 'Congo The Democratic Republic Of The',
            ),
            50 => 
            array (
                'id' => 51,
                'sortname' => 'CK',
                'name' => 'Cook Islands',
            ),
            51 => 
            array (
                'id' => 52,
                'sortname' => 'CR',
                'name' => 'Costa Rica',
            ),
            52 => 
            array (
                'id' => 53,
                'sortname' => 'CI',
            'name' => 'Cote D\'Ivoire (Ivory Coast)',
            ),
            53 => 
            array (
                'id' => 54,
                'sortname' => 'HR',
            'name' => 'Croatia (Hrvatska)',
            ),
            54 => 
            array (
                'id' => 55,
                'sortname' => 'CU',
                'name' => 'Cuba',
            ),
            55 => 
            array (
                'id' => 56,
                'sortname' => 'CY',
                'name' => 'Cyprus',
            ),
            56 => 
            array (
                'id' => 57,
                'sortname' => 'CZ',
                'name' => 'Czech Republic',
            ),
            57 => 
            array (
                'id' => 58,
                'sortname' => 'DK',
                'name' => 'Denmark',
            ),
            58 => 
            array (
                'id' => 59,
                'sortname' => 'DJ',
                'name' => 'Djibouti',
            ),
            59 => 
            array (
                'id' => 60,
                'sortname' => 'DM',
                'name' => 'Dominica',
            ),
            60 => 
            array (
                'id' => 61,
                'sortname' => 'DO',
                'name' => 'Dominican Republic',
            ),
            61 => 
            array (
                'id' => 62,
                'sortname' => 'TP',
                'name' => 'East Timor',
            ),
            62 => 
            array (
                'id' => 63,
                'sortname' => 'EC',
                'name' => 'Ecuador',
            ),
            63 => 
            array (
                'id' => 64,
                'sortname' => 'EG',
                'name' => 'Egypt',
            ),
            64 => 
            array (
                'id' => 65,
                'sortname' => 'SV',
                'name' => 'El Salvador',
            ),
            65 => 
            array (
                'id' => 66,
                'sortname' => 'GQ',
                'name' => 'Equatorial Guinea',
            ),
            66 => 
            array (
                'id' => 67,
                'sortname' => 'ER',
                'name' => 'Eritrea',
            ),
            67 => 
            array (
                'id' => 68,
                'sortname' => 'EE',
                'name' => 'Estonia',
            ),
            68 => 
            array (
                'id' => 69,
                'sortname' => 'ET',
                'name' => 'Ethiopia',
            ),
            69 => 
            array (
                'id' => 70,
                'sortname' => 'XA',
                'name' => 'External Territories of Australia',
            ),
            70 => 
            array (
                'id' => 71,
                'sortname' => 'FK',
                'name' => 'Falkland Islands',
            ),
            71 => 
            array (
                'id' => 72,
                'sortname' => 'FO',
                'name' => 'Faroe Islands',
            ),
            72 => 
            array (
                'id' => 73,
                'sortname' => 'FJ',
                'name' => 'Fiji Islands',
            ),
            73 => 
            array (
                'id' => 74,
                'sortname' => 'FI',
                'name' => 'Finland',
            ),
            74 => 
            array (
                'id' => 75,
                'sortname' => 'FR',
                'name' => 'France',
            ),
            75 => 
            array (
                'id' => 76,
                'sortname' => 'GF',
                'name' => 'French Guiana',
            ),
            76 => 
            array (
                'id' => 77,
                'sortname' => 'PF',
                'name' => 'French Polynesia',
            ),
            77 => 
            array (
                'id' => 78,
                'sortname' => 'TF',
                'name' => 'French Southern Territories',
            ),
            78 => 
            array (
                'id' => 79,
                'sortname' => 'GA',
                'name' => 'Gabon',
            ),
            79 => 
            array (
                'id' => 80,
                'sortname' => 'GM',
                'name' => 'Gambia The',
            ),
            80 => 
            array (
                'id' => 81,
                'sortname' => 'GE',
                'name' => 'Georgia',
            ),
            81 => 
            array (
                'id' => 82,
                'sortname' => 'DE',
                'name' => 'Germany',
            ),
            82 => 
            array (
                'id' => 83,
                'sortname' => 'GH',
                'name' => 'Ghana',
            ),
            83 => 
            array (
                'id' => 84,
                'sortname' => 'GI',
                'name' => 'Gibraltar',
            ),
            84 => 
            array (
                'id' => 85,
                'sortname' => 'GR',
                'name' => 'Greece',
            ),
            85 => 
            array (
                'id' => 86,
                'sortname' => 'GL',
                'name' => 'Greenland',
            ),
            86 => 
            array (
                'id' => 87,
                'sortname' => 'GD',
                'name' => 'Grenada',
            ),
            87 => 
            array (
                'id' => 88,
                'sortname' => 'GP',
                'name' => 'Guadeloupe',
            ),
            88 => 
            array (
                'id' => 89,
                'sortname' => 'GU',
                'name' => 'Guam',
            ),
            89 => 
            array (
                'id' => 90,
                'sortname' => 'GT',
                'name' => 'Guatemala',
            ),
            90 => 
            array (
                'id' => 91,
                'sortname' => 'XU',
                'name' => 'Guernsey and Alderney',
            ),
            91 => 
            array (
                'id' => 92,
                'sortname' => 'GN',
                'name' => 'Guinea',
            ),
            92 => 
            array (
                'id' => 93,
                'sortname' => 'GW',
                'name' => 'Guinea-Bissau',
            ),
            93 => 
            array (
                'id' => 94,
                'sortname' => 'GY',
                'name' => 'Guyana',
            ),
            94 => 
            array (
                'id' => 95,
                'sortname' => 'HT',
                'name' => 'Haiti',
            ),
            95 => 
            array (
                'id' => 96,
                'sortname' => 'HM',
                'name' => 'Heard and McDonald Islands',
            ),
            96 => 
            array (
                'id' => 97,
                'sortname' => 'HN',
                'name' => 'Honduras',
            ),
            97 => 
            array (
                'id' => 98,
                'sortname' => 'HK',
                'name' => 'Hong Kong S.A.R.',
            ),
            98 => 
            array (
                'id' => 99,
                'sortname' => 'HU',
                'name' => 'Hungary',
            ),
            99 => 
            array (
                'id' => 100,
                'sortname' => 'IS',
                'name' => 'Iceland',
            ),
            100 => 
            array (
                'id' => 101,
                'sortname' => 'IN',
                'name' => 'India',
            ),
            101 => 
            array (
                'id' => 102,
                'sortname' => 'ID',
                'name' => 'Indonesia',
            ),
            102 => 
            array (
                'id' => 103,
                'sortname' => 'IR',
                'name' => 'Iran',
            ),
            103 => 
            array (
                'id' => 104,
                'sortname' => 'IQ',
                'name' => 'Iraq',
            ),
            104 => 
            array (
                'id' => 105,
                'sortname' => 'IE',
                'name' => 'Ireland',
            ),
            105 => 
            array (
                'id' => 106,
                'sortname' => 'IL',
                'name' => 'Israel',
            ),
            106 => 
            array (
                'id' => 107,
                'sortname' => 'IT',
                'name' => 'Italy',
            ),
            107 => 
            array (
                'id' => 108,
                'sortname' => 'JM',
                'name' => 'Jamaica',
            ),
            108 => 
            array (
                'id' => 109,
                'sortname' => 'JP',
                'name' => 'Japan',
            ),
            109 => 
            array (
                'id' => 110,
                'sortname' => 'XJ',
                'name' => 'Jersey',
            ),
            110 => 
            array (
                'id' => 111,
                'sortname' => 'JO',
                'name' => 'Jordan',
            ),
            111 => 
            array (
                'id' => 112,
                'sortname' => 'KZ',
                'name' => 'Kazakhstan',
            ),
            112 => 
            array (
                'id' => 113,
                'sortname' => 'KE',
                'name' => 'Kenya',
            ),
            113 => 
            array (
                'id' => 114,
                'sortname' => 'KI',
                'name' => 'Kiribati',
            ),
            114 => 
            array (
                'id' => 115,
                'sortname' => 'KP',
                'name' => 'Korea North',
            ),
            115 => 
            array (
                'id' => 116,
                'sortname' => 'KR',
                'name' => 'Korea South',
            ),
            116 => 
            array (
                'id' => 117,
                'sortname' => 'KW',
                'name' => 'Kuwait',
            ),
            117 => 
            array (
                'id' => 118,
                'sortname' => 'KG',
                'name' => 'Kyrgyzstan',
            ),
            118 => 
            array (
                'id' => 119,
                'sortname' => 'LA',
                'name' => 'Laos',
            ),
            119 => 
            array (
                'id' => 120,
                'sortname' => 'LV',
                'name' => 'Latvia',
            ),
            120 => 
            array (
                'id' => 121,
                'sortname' => 'LB',
                'name' => 'Lebanon',
            ),
            121 => 
            array (
                'id' => 122,
                'sortname' => 'LS',
                'name' => 'Lesotho',
            ),
            122 => 
            array (
                'id' => 123,
                'sortname' => 'LR',
                'name' => 'Liberia',
            ),
            123 => 
            array (
                'id' => 124,
                'sortname' => 'LY',
                'name' => 'Libya',
            ),
            124 => 
            array (
                'id' => 125,
                'sortname' => 'LI',
                'name' => 'Liechtenstein',
            ),
            125 => 
            array (
                'id' => 126,
                'sortname' => 'LT',
                'name' => 'Lithuania',
            ),
            126 => 
            array (
                'id' => 127,
                'sortname' => 'LU',
                'name' => 'Luxembourg',
            ),
            127 => 
            array (
                'id' => 128,
                'sortname' => 'MO',
                'name' => 'Macau S.A.R.',
            ),
            128 => 
            array (
                'id' => 129,
                'sortname' => 'MK',
                'name' => 'Macedonia',
            ),
            129 => 
            array (
                'id' => 130,
                'sortname' => 'MG',
                'name' => 'Madagascar',
            ),
            130 => 
            array (
                'id' => 131,
                'sortname' => 'MW',
                'name' => 'Malawi',
            ),
            131 => 
            array (
                'id' => 132,
                'sortname' => 'MY',
                'name' => 'Malaysia',
            ),
            132 => 
            array (
                'id' => 133,
                'sortname' => 'MV',
                'name' => 'Maldives',
            ),
            133 => 
            array (
                'id' => 134,
                'sortname' => 'ML',
                'name' => 'Mali',
            ),
            134 => 
            array (
                'id' => 135,
                'sortname' => 'MT',
                'name' => 'Malta',
            ),
            135 => 
            array (
                'id' => 136,
                'sortname' => 'XM',
            'name' => 'Man (Isle of)',
            ),
            136 => 
            array (
                'id' => 137,
                'sortname' => 'MH',
                'name' => 'Marshall Islands',
            ),
            137 => 
            array (
                'id' => 138,
                'sortname' => 'MQ',
                'name' => 'Martinique',
            ),
            138 => 
            array (
                'id' => 139,
                'sortname' => 'MR',
                'name' => 'Mauritania',
            ),
            139 => 
            array (
                'id' => 140,
                'sortname' => 'MU',
                'name' => 'Mauritius',
            ),
            140 => 
            array (
                'id' => 141,
                'sortname' => 'YT',
                'name' => 'Mayotte',
            ),
            141 => 
            array (
                'id' => 142,
                'sortname' => 'MX',
                'name' => 'Mexico',
            ),
            142 => 
            array (
                'id' => 143,
                'sortname' => 'FM',
                'name' => 'Micronesia',
            ),
            143 => 
            array (
                'id' => 144,
                'sortname' => 'MD',
                'name' => 'Moldova',
            ),
            144 => 
            array (
                'id' => 145,
                'sortname' => 'MC',
                'name' => 'Monaco',
            ),
            145 => 
            array (
                'id' => 146,
                'sortname' => 'MN',
                'name' => 'Mongolia',
            ),
            146 => 
            array (
                'id' => 147,
                'sortname' => 'MS',
                'name' => 'Montserrat',
            ),
            147 => 
            array (
                'id' => 148,
                'sortname' => 'MA',
                'name' => 'Morocco',
            ),
            148 => 
            array (
                'id' => 149,
                'sortname' => 'MZ',
                'name' => 'Mozambique',
            ),
            149 => 
            array (
                'id' => 150,
                'sortname' => 'MM',
                'name' => 'Myanmar',
            ),
            150 => 
            array (
                'id' => 151,
                'sortname' => 'NA',
                'name' => 'Namibia',
            ),
            151 => 
            array (
                'id' => 152,
                'sortname' => 'NR',
                'name' => 'Nauru',
            ),
            152 => 
            array (
                'id' => 153,
                'sortname' => 'NP',
                'name' => 'Nepal',
            ),
            153 => 
            array (
                'id' => 154,
                'sortname' => 'AN',
                'name' => 'Netherlands Antilles',
            ),
            154 => 
            array (
                'id' => 155,
                'sortname' => 'NL',
                'name' => 'Netherlands The',
            ),
            155 => 
            array (
                'id' => 156,
                'sortname' => 'NC',
                'name' => 'New Caledonia',
            ),
            156 => 
            array (
                'id' => 157,
                'sortname' => 'NZ',
                'name' => 'New Zealand',
            ),
            157 => 
            array (
                'id' => 158,
                'sortname' => 'NI',
                'name' => 'Nicaragua',
            ),
            158 => 
            array (
                'id' => 159,
                'sortname' => 'NE',
                'name' => 'Niger',
            ),
            159 => 
            array (
                'id' => 160,
                'sortname' => 'NG',
                'name' => 'Nigeria',
            ),
            160 => 
            array (
                'id' => 161,
                'sortname' => 'NU',
                'name' => 'Niue',
            ),
            161 => 
            array (
                'id' => 162,
                'sortname' => 'NF',
                'name' => 'Norfolk Island',
            ),
            162 => 
            array (
                'id' => 163,
                'sortname' => 'MP',
                'name' => 'Northern Mariana Islands',
            ),
            163 => 
            array (
                'id' => 164,
                'sortname' => 'NO',
                'name' => 'Norway',
            ),
            164 => 
            array (
                'id' => 165,
                'sortname' => 'OM',
                'name' => 'Oman',
            ),
            165 => 
            array (
                'id' => 166,
                'sortname' => 'PK',
                'name' => 'Pakistan',
            ),
            166 => 
            array (
                'id' => 167,
                'sortname' => 'PW',
                'name' => 'Palau',
            ),
            167 => 
            array (
                'id' => 168,
                'sortname' => 'PS',
                'name' => 'Palestinian Territory Occupied',
            ),
            168 => 
            array (
                'id' => 169,
                'sortname' => 'PA',
                'name' => 'Panama',
            ),
            169 => 
            array (
                'id' => 170,
                'sortname' => 'PG',
                'name' => 'Papua new Guinea',
            ),
            170 => 
            array (
                'id' => 171,
                'sortname' => 'PY',
                'name' => 'Paraguay',
            ),
            171 => 
            array (
                'id' => 172,
                'sortname' => 'PE',
                'name' => 'Peru',
            ),
            172 => 
            array (
                'id' => 173,
                'sortname' => 'PH',
                'name' => 'Philippines',
            ),
            173 => 
            array (
                'id' => 174,
                'sortname' => 'PN',
                'name' => 'Pitcairn Island',
            ),
            174 => 
            array (
                'id' => 175,
                'sortname' => 'PL',
                'name' => 'Poland',
            ),
            175 => 
            array (
                'id' => 176,
                'sortname' => 'PT',
                'name' => 'Portugal',
            ),
            176 => 
            array (
                'id' => 177,
                'sortname' => 'PR',
                'name' => 'Puerto Rico',
            ),
            177 => 
            array (
                'id' => 178,
                'sortname' => 'QA',
                'name' => 'Qatar',
            ),
            178 => 
            array (
                'id' => 179,
                'sortname' => 'RE',
                'name' => 'Reunion',
            ),
            179 => 
            array (
                'id' => 180,
                'sortname' => 'RO',
                'name' => 'Romania',
            ),
            180 => 
            array (
                'id' => 181,
                'sortname' => 'RU',
                'name' => 'Russia',
            ),
            181 => 
            array (
                'id' => 182,
                'sortname' => 'RW',
                'name' => 'Rwanda',
            ),
            182 => 
            array (
                'id' => 183,
                'sortname' => 'SH',
                'name' => 'Saint Helena',
            ),
            183 => 
            array (
                'id' => 184,
                'sortname' => 'KN',
                'name' => 'Saint Kitts And Nevis',
            ),
            184 => 
            array (
                'id' => 185,
                'sortname' => 'LC',
                'name' => 'Saint Lucia',
            ),
            185 => 
            array (
                'id' => 186,
                'sortname' => 'PM',
                'name' => 'Saint Pierre and Miquelon',
            ),
            186 => 
            array (
                'id' => 187,
                'sortname' => 'VC',
                'name' => 'Saint Vincent And The Grenadines',
            ),
            187 => 
            array (
                'id' => 188,
                'sortname' => 'WS',
                'name' => 'Samoa',
            ),
            188 => 
            array (
                'id' => 189,
                'sortname' => 'SM',
                'name' => 'San Marino',
            ),
            189 => 
            array (
                'id' => 190,
                'sortname' => 'ST',
                'name' => 'Sao Tome and Principe',
            ),
            190 => 
            array (
                'id' => 191,
                'sortname' => 'SA',
                'name' => 'Saudi Arabia',
            ),
            191 => 
            array (
                'id' => 192,
                'sortname' => 'SN',
                'name' => 'Senegal',
            ),
            192 => 
            array (
                'id' => 193,
                'sortname' => 'RS',
                'name' => 'Serbia',
            ),
            193 => 
            array (
                'id' => 194,
                'sortname' => 'SC',
                'name' => 'Seychelles',
            ),
            194 => 
            array (
                'id' => 195,
                'sortname' => 'SL',
                'name' => 'Sierra Leone',
            ),
            195 => 
            array (
                'id' => 196,
                'sortname' => 'SG',
                'name' => 'Singapore',
            ),
            196 => 
            array (
                'id' => 197,
                'sortname' => 'SK',
                'name' => 'Slovakia',
            ),
            197 => 
            array (
                'id' => 198,
                'sortname' => 'SI',
                'name' => 'Slovenia',
            ),
            198 => 
            array (
                'id' => 199,
                'sortname' => 'XG',
                'name' => 'Smaller Territories of the UK',
            ),
            199 => 
            array (
                'id' => 200,
                'sortname' => 'SB',
                'name' => 'Solomon Islands',
            ),
            200 => 
            array (
                'id' => 201,
                'sortname' => 'SO',
                'name' => 'Somalia',
            ),
            201 => 
            array (
                'id' => 202,
                'sortname' => 'ZA',
                'name' => 'South Africa',
            ),
            202 => 
            array (
                'id' => 203,
                'sortname' => 'GS',
                'name' => 'South Georgia',
            ),
            203 => 
            array (
                'id' => 204,
                'sortname' => 'SS',
                'name' => 'South Sudan',
            ),
            204 => 
            array (
                'id' => 205,
                'sortname' => 'ES',
                'name' => 'Spain',
            ),
            205 => 
            array (
                'id' => 206,
                'sortname' => 'LK',
                'name' => 'Sri Lanka',
            ),
            206 => 
            array (
                'id' => 207,
                'sortname' => 'SD',
                'name' => 'Sudan',
            ),
            207 => 
            array (
                'id' => 208,
                'sortname' => 'SR',
                'name' => 'Suriname',
            ),
            208 => 
            array (
                'id' => 209,
                'sortname' => 'SJ',
                'name' => 'Svalbard And Jan Mayen Islands',
            ),
            209 => 
            array (
                'id' => 210,
                'sortname' => 'SZ',
                'name' => 'Swaziland',
            ),
            210 => 
            array (
                'id' => 211,
                'sortname' => 'SE',
                'name' => 'Sweden',
            ),
            211 => 
            array (
                'id' => 212,
                'sortname' => 'CH',
                'name' => 'Switzerland',
            ),
            212 => 
            array (
                'id' => 213,
                'sortname' => 'SY',
                'name' => 'Syria',
            ),
            213 => 
            array (
                'id' => 214,
                'sortname' => 'TW',
                'name' => 'Taiwan',
            ),
            214 => 
            array (
                'id' => 215,
                'sortname' => 'TJ',
                'name' => 'Tajikistan',
            ),
            215 => 
            array (
                'id' => 216,
                'sortname' => 'TZ',
                'name' => 'Tanzania',
            ),
            216 => 
            array (
                'id' => 217,
                'sortname' => 'TH',
                'name' => 'Thailand',
            ),
            217 => 
            array (
                'id' => 218,
                'sortname' => 'TG',
                'name' => 'Togo',
            ),
            218 => 
            array (
                'id' => 219,
                'sortname' => 'TK',
                'name' => 'Tokelau',
            ),
            219 => 
            array (
                'id' => 220,
                'sortname' => 'TO',
                'name' => 'Tonga',
            ),
            220 => 
            array (
                'id' => 221,
                'sortname' => 'TT',
                'name' => 'Trinidad And Tobago',
            ),
            221 => 
            array (
                'id' => 222,
                'sortname' => 'TN',
                'name' => 'Tunisia',
            ),
            222 => 
            array (
                'id' => 223,
                'sortname' => 'TR',
                'name' => 'Turkey',
            ),
            223 => 
            array (
                'id' => 224,
                'sortname' => 'TM',
                'name' => 'Turkmenistan',
            ),
            224 => 
            array (
                'id' => 225,
                'sortname' => 'TC',
                'name' => 'Turks And Caicos Islands',
            ),
            225 => 
            array (
                'id' => 226,
                'sortname' => 'TV',
                'name' => 'Tuvalu',
            ),
            226 => 
            array (
                'id' => 227,
                'sortname' => 'UG',
                'name' => 'Uganda',
            ),
            227 => 
            array (
                'id' => 228,
                'sortname' => 'UA',
                'name' => 'Ukraine',
            ),
            228 => 
            array (
                'id' => 229,
                'sortname' => 'AE',
                'name' => 'United Arab Emirates',
            ),
            229 => 
            array (
                'id' => 230,
                'sortname' => 'GB',
                'name' => 'United Kingdom',
            ),
            230 => 
            array (
                'id' => 231,
                'sortname' => 'US',
                'name' => 'United States',
            ),
            231 => 
            array (
                'id' => 232,
                'sortname' => 'UM',
                'name' => 'United States Minor Outlying Islands',
            ),
            232 => 
            array (
                'id' => 233,
                'sortname' => 'UY',
                'name' => 'Uruguay',
            ),
            233 => 
            array (
                'id' => 234,
                'sortname' => 'UZ',
                'name' => 'Uzbekistan',
            ),
            234 => 
            array (
                'id' => 235,
                'sortname' => 'VU',
                'name' => 'Vanuatu',
            ),
            235 => 
            array (
                'id' => 236,
                'sortname' => 'VA',
            'name' => 'Vatican City State (Holy See)',
            ),
            236 => 
            array (
                'id' => 237,
                'sortname' => 'VE',
                'name' => 'Venezuela',
            ),
            237 => 
            array (
                'id' => 238,
                'sortname' => 'VN',
                'name' => 'Vietnam',
            ),
            238 => 
            array (
                'id' => 239,
                'sortname' => 'VG',
            'name' => 'Virgin Islands (British)',
            ),
            239 => 
            array (
                'id' => 240,
                'sortname' => 'VI',
            'name' => 'Virgin Islands (US)',
            ),
            240 => 
            array (
                'id' => 241,
                'sortname' => 'WF',
                'name' => 'Wallis And Futuna Islands',
            ),
            241 => 
            array (
                'id' => 242,
                'sortname' => 'EH',
                'name' => 'Western Sahara',
            ),
            242 => 
            array (
                'id' => 243,
                'sortname' => 'YE',
                'name' => 'Yemen',
            ),
            243 => 
            array (
                'id' => 244,
                'sortname' => 'YU',
                'name' => 'Yugoslavia',
            ),
            244 => 
            array (
                'id' => 245,
                'sortname' => 'ZM',
                'name' => 'Zambia',
            ),
            245 => 
            array (
                'id' => 246,
                'sortname' => 'ZW',
                'name' => 'Zimbabwe',
            ),
        ));
        
        
    }
}