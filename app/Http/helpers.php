<?php
use App\Models\User;
use App\Models\Settings;
use App\Models\Listings;
use App\Models\PaymentGateway;
use App\Models\SubscriptionPlan;
use App\Models\Reviews;

  
if (! function_exists('countUserListing')) {
    function countUserListing($user_id)
    { 
        $total_listings= Listings::where('user_id',$user_id)->where('status',1)->count();
        
        return $total_listings;
        
    }
}

if (! function_exists('checkUserPlanFeatures')) {
    function checkUserPlanFeatures($user_id,$field_name)
    { 
        $user_obj= User::find($user_id);

        $user_plan_id = $user_obj->plan_id;

        $plan_info = SubscriptionPlan::find($user_plan_id);
    
        if(isset($plan_info))
        { 
    
            return $plan_info->$field_name;
        }
        else
        { 
            return '';
        }
         
    }
}

if (! function_exists('getPaymentGatewayInfo')) {
    function getPaymentGatewayInfo($id,$field_name=null)
    { 
     
        $gateway_obj= PaymentGateway::find($id); 
    
        if(isset($field_name))
        {
            $gateway_info=json_decode($gateway_obj->gateway_info);
    
            //echo $gateway_info->status;
            //exit;
    
            return $gateway_info->$field_name;
        }
        else
        { 
            return $gateway_obj;
        }
         
    }
}

if (! function_exists('getcong')) {

    function getcong($key)
    {
        if(file_exists(base_path('/public/.lic')))
       { 
            $settings = Settings::findOrFail('1'); 

            return $settings->$key;
       }    	 
         
    }
}

if (!function_exists('classActivePath')) {
    function classActivePath($path)
    {
        $path = explode('.', $path);
        $segment = 2;
        foreach($path as $p) {
            if((request()->segment($segment) == $p) == false) {
                return '';
            }
            $segment++;
        }
        return ' active';
    }
}

if (!function_exists('classActivePathSite')) {
    function classActivePathSite($path)
    {
        $path = explode('.', $path);
        $segment = 1;
        foreach($path as $p) {
            if((request()->segment($segment) == $p) == false) {
                return '';
            }
            $segment++;
        }
        return 'active';
    }
}

if (!function_exists('checkListingsExpDate')) {
    
    function checkListingsExpDate()
    {   
        $todayDate=strtotime(date('m/d/Y'));
         
        $listings = Listings::where('status','1')->where('listing_exp_date','<=',$todayDate)->get();

        foreach ($listings as $listing_data) {

            $listing_obj = Listings::findOrFail($listing_data['id']);
            
            $listing_obj->status = 0;              
            $listing_obj->save(); 

        }
         
    }

}

if (! function_exists('putPermanentEnv')) {

    function putPermanentEnv($key, $value)
   {
       $path = app()->environmentFilePath();
   
       $escaped = preg_quote('='.env($key), '/');
   
       file_put_contents($path, preg_replace(
           "/^{$key}{$escaped}/m",
           "{$key}={$value}",
           file_get_contents($path)
       ));
   }
   
   }

if (!function_exists('generate_timezone_list')) {
    function generate_timezone_list()
    {
        static $regions = array(
            DateTimeZone::AFRICA,
            DateTimeZone::AMERICA,
            DateTimeZone::ANTARCTICA,
            DateTimeZone::ASIA,
            DateTimeZone::ATLANTIC,
            DateTimeZone::AUSTRALIA,
            DateTimeZone::EUROPE,
            DateTimeZone::INDIAN,
            DateTimeZone::PACIFIC,
        );
    
        $timezones = array();
        foreach( $regions as $region )
        {
            $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
        }
    
        $timezone_offsets = array();
        foreach( $timezones as $timezone )
        {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
        }
    
        // sort timezone by offset
        ksort($timezone_offsets);
    
        $timezone_list = array();
        foreach( $timezone_offsets as $timezone => $offset )
        {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate( 'H:i', abs($offset) );
    
            $pretty_offset = "UTC{$offset_prefix}{$offset_formatted}";
    
            $timezone_list[$timezone] = "({$pretty_offset}) $timezone";
        }
    
        return $timezone_list;
    }
    
    }

if (! function_exists('getCurrencySymbols')) {
    function getCurrencySymbols($code)
    { 
        $currency_symbols = array(
                            'AED' => '&#1583;.&#1573;', // ?
                            'AFN' => '&#65;&#102;',
                            'ALL' => '&#76;&#101;&#107;',
                            'AMD' => '',
                            'ANG' => '&#402;',
                            'AOA' => '&#75;&#122;', // ?
                            'ARS' => '&#36;',
                            'AUD' => '&#36;',
                            'AWG' => '&#402;',
                            'AZN' => '&#1084;&#1072;&#1085;',
                            'BAM' => '&#75;&#77;',
                            'BBD' => '&#36;',
                            'BDT' => '&#2547;', // ?
                            'BGN' => '&#1083;&#1074;',
                            'BHD' => '.&#1583;.&#1576;', // ?
                            'BIF' => '&#70;&#66;&#117;', // ?
                            'BMD' => '&#36;',
                            'BND' => '&#36;',
                            'BOB' => '&#36;&#98;',
                            'BRL' => '&#82;&#36;',
                            'BSD' => '&#36;',
                            'BTN' => '&#78;&#117;&#46;', // ?
                            'BWP' => '&#80;',
                            'BYR' => '&#112;&#46;',
                            'BZD' => '&#66;&#90;&#36;',
                            'CAD' => '&#36;',
                            'CDF' => '&#70;&#67;',
                            'CHF' => '&#67;&#72;&#70;',
                            'CLF' => '', // ?
                            'CLP' => '&#36;',
                            'CNY' => '&#165;',
                            'COP' => '&#36;',
                            'CRC' => '&#8353;',
                            'CUP' => '&#8396;',
                            'CVE' => '&#36;', // ?
                            'CZK' => '&#75;&#269;',
                            'DJF' => '&#70;&#100;&#106;', // ?
                            'DKK' => '&#107;&#114;',
                            'DOP' => '&#82;&#68;&#36;',
                            'DZD' => '&#1583;&#1580;', // ?
                            'EGP' => '&#163;',
                            'ETB' => '&#66;&#114;',
                            'EUR' => '&#8364;',
                            'FJD' => '&#36;',
                            'FKP' => '&#163;',
                            'GBP' => '&#163;',
                            'GEL' => '&#4314;', // ?
                            'GHS' => '&#162;',
                            'GIP' => '&#163;',
                            'GMD' => '&#68;', // ?
                            'GNF' => '&#70;&#71;', // ?
                            'GTQ' => '&#81;',
                            'GYD' => '&#36;',
                            'HKD' => '&#36;',
                            'HNL' => '&#76;',
                            'HRK' => '&#107;&#110;',
                            'HTG' => '&#71;', // ?
                            'HUF' => '&#70;&#116;',
                            'IDR' => '&#82;&#112;',
                            'ILS' => '&#8362;',
                            'INR' => '&#8377;',
                            'IQD' => '&#1593;.&#1583;', // ?
                            'IRR' => '&#65020;',
                            'ISK' => '&#107;&#114;',
                            'JEP' => '&#163;',
                            'JMD' => '&#74;&#36;',
                            'JOD' => '&#74;&#68;', // ?
                            'JPY' => '&#165;',
                            'KES' => '&#75;&#83;&#104;', // ?
                            'KGS' => '&#1083;&#1074;',
                            'KHR' => '&#6107;',
                            'KMF' => '&#67;&#70;', // ?
                            'KPW' => '&#8361;',
                            'KRW' => '&#8361;',
                            'KWD' => '&#1583;.&#1603;', // ?
                            'KYD' => '&#36;',
                            'KZT' => '&#1083;&#1074;',
                            'LAK' => '&#8365;',
                            'LBP' => '&#163;',
                            'LKR' => '&#8360;',
                            'LRD' => '&#36;',
                            'LSL' => '&#76;', // ?
                            'LTL' => '&#76;&#116;',
                            'LVL' => '&#76;&#115;',
                            'LYD' => '&#1604;.&#1583;', // ?
                            'MAD' => '&#1583;.&#1605;.', //?
                            'MDL' => '&#76;',
                            'MGA' => '&#65;&#114;', // ?
                            'MKD' => '&#1076;&#1077;&#1085;',
                            'MMK' => '&#75;',
                            'MNT' => '&#8366;',
                            'MOP' => '&#77;&#79;&#80;&#36;', // ?
                            'MRO' => '&#85;&#77;', // ?
                            'MUR' => '&#8360;', // ?
                            'MVR' => '.&#1923;', // ?
                            'MWK' => '&#77;&#75;',
                            'MXN' => '&#36;',
                            'MYR' => '&#82;&#77;',
                            'MZN' => '&#77;&#84;',
                            'NAD' => '&#36;',
                            'NGN' => '&#8358;',
                            'NIO' => '&#67;&#36;',
                            'NOK' => '&#107;&#114;',
                            'NPR' => '&#8360;',
                            'NZD' => '&#36;',
                            'OMR' => '&#65020;',
                            'PAB' => '&#66;&#47;&#46;',
                            'PEN' => '&#83;&#47;&#46;',
                            'PGK' => '&#75;', // ?
                            'PHP' => '&#8369;',
                            'PKR' => '&#8360;',
                            'PLN' => '&#122;&#322;',
                            'PYG' => '&#71;&#115;',
                            'QAR' => '&#65020;',
                            'RON' => '&#108;&#101;&#105;',
                            'RSD' => '&#1044;&#1080;&#1085;&#46;',
                            'RUB' => '&#1088;&#1091;&#1073;',
                            'RWF' => '&#1585;.&#1587;',
                            'SAR' => '&#65020;',
                            'SBD' => '&#36;',
                            'SCR' => '&#8360;',
                            'SDG' => '&#163;', // ?
                            'SEK' => '&#107;&#114;',
                            'SGD' => '&#36;',
                            'SHP' => '&#163;',
                            'SLL' => '&#76;&#101;', // ?
                            'SOS' => '&#83;',
                            'SRD' => '&#36;',
                            'STD' => '&#68;&#98;', // ?
                            'SVC' => '&#36;',
                            'SYP' => '&#163;',
                            'SZL' => '&#76;', // ?
                            'THB' => '&#3647;',
                            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
                            'TMT' => '&#109;',
                            'TND' => '&#1583;.&#1578;',
                            'TOP' => '&#84;&#36;',
                            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
                            'TTD' => '&#36;',
                            'TWD' => '&#78;&#84;&#36;',
                            'TZS' => '',
                            'UAH' => '&#8372;',
                            'UGX' => '&#85;&#83;&#104;',
                            'USD' => '&#36;',
                            'UYU' => '&#36;&#85;',
                            'UZS' => '&#1083;&#1074;',
                            'VEF' => '&#66;&#115;',
                            'VND' => '&#8363;',
                            'VUV' => '&#86;&#84;',
                            'WST' => '&#87;&#83;&#36;',
                            'XAF' => '&#70;&#67;&#70;&#65;',
                            'XCD' => '&#36;',
                            'XDR' => '',
                            'XOF' => '',
                            'XPF' => '&#70;',
                            'YER' => '&#65020;',
                            'ZAR' => '&#82;',
                            'ZMK' => '&#90;&#75;', // ?
                            'ZWL' => '&#90;&#36;',
                        );
            
            $currency_html_code=$currency_symbols[$code];

            return $currency_html_code;
    }

}

if (! function_exists('getCurrencyList')) {
    function getCurrencyList()
    {                   
            // count 164
            $currency_list = array(
                "AFA" => "Afghan Afghani",
                "ALL" => "Albanian Lek",
                "DZD" => "Algerian Dinar",
                "AOA" => "Angolan Kwanza",
                "ARS" => "Argentine Peso",
                "AMD" => "Armenian Dram",
                "AWG" => "Aruban Florin",
                "AUD" => "Australian Dollar",
                "AZN" => "Azerbaijani Manat",
                "BSD" => "Bahamian Dollar",
                "BHD" => "Bahraini Dinar",
                "BDT" => "Bangladeshi Taka",
                "BBD" => "Barbadian Dollar",
                "BYR" => "Belarusian Ruble",
                "BEF" => "Belgian Franc",
                "BZD" => "Belize Dollar",
                "BMD" => "Bermudan Dollar",
                "BTN" => "Bhutanese Ngultrum",
                "BTC" => "Bitcoin",
                "BOB" => "Bolivian Boliviano",
                "BAM" => "Bosnia",
                "BWP" => "Botswanan Pula",
                "BRL" => "Brazilian Real",
                "GBP" => "British Pound Sterling",
                "BND" => "Brunei Dollar",
                "BGN" => "Bulgarian Lev",
                "BIF" => "Burundian Franc",
                "KHR" => "Cambodian Riel",
                "CAD" => "Canadian Dollar",
                "CVE" => "Cape Verdean Escudo",
                "KYD" => "Cayman Islands Dollar",
                "XOF" => "CFA Franc BCEAO",
                "XAF" => "CFA Franc BEAC",
                "XPF" => "CFP Franc",
                "CLP" => "Chilean Peso",
                "CNY" => "Chinese Yuan",
                "COP" => "Colombian Peso",
                "KMF" => "Comorian Franc",
                "CDF" => "Congolese Franc",
                "CRC" => "Costa Rican ColÃ³n",
                "HRK" => "Croatian Kuna",
                "CUC" => "Cuban Convertible Peso",
                "CZK" => "Czech Republic Koruna",
                "DKK" => "Danish Krone",
                "DJF" => "Djiboutian Franc",
                "DOP" => "Dominican Peso",
                "XCD" => "East Caribbean Dollar",
                "EGP" => "Egyptian Pound",
                "ERN" => "Eritrean Nakfa",
                "EEK" => "Estonian Kroon",
                "ETB" => "Ethiopian Birr",
                "EUR" => "Euro",
                "FKP" => "Falkland Islands Pound",
                "FJD" => "Fijian Dollar",
                "GMD" => "Gambian Dalasi",
                "GEL" => "Georgian Lari",
                "DEM" => "German Mark",
                "GHS" => "Ghanaian Cedi",
                "GIP" => "Gibraltar Pound",
                "GRD" => "Greek Drachma",
                "GTQ" => "Guatemalan Quetzal",
                "GNF" => "Guinean Franc",
                "GYD" => "Guyanaese Dollar",
                "HTG" => "Haitian Gourde",
                "HNL" => "Honduran Lempira",
                "HKD" => "Hong Kong Dollar",
                "HUF" => "Hungarian Forint",
                "ISK" => "Icelandic KrÃ³na",
                "INR" => "Indian Rupee",
                "IDR" => "Indonesian Rupiah",
                "IRR" => "Iranian Rial",
                "IQD" => "Iraqi Dinar",
                "ILS" => "Israeli New Sheqel",
                "ITL" => "Italian Lira",
                "JMD" => "Jamaican Dollar",
                "JPY" => "Japanese Yen",
                "JOD" => "Jordanian Dinar",
                "KZT" => "Kazakhstani Tenge",
                "KES" => "Kenyan Shilling",
                "KWD" => "Kuwaiti Dinar",
                "KGS" => "Kyrgystani Som",
                "LAK" => "Laotian Kip",
                "LVL" => "Latvian Lats",
                "LBP" => "Lebanese Pound",
                "LSL" => "Lesotho Loti",
                "LRD" => "Liberian Dollar",
                "LYD" => "Libyan Dinar",
                "LTL" => "Lithuanian Litas",
                "MOP" => "Macanese Pataca",
                "MKD" => "Macedonian Denar",
                "MGA" => "Malagasy Ariary",
                "MWK" => "Malawian Kwacha",
                "MYR" => "Malaysian Ringgit",
                "MVR" => "Maldivian Rufiyaa",
                "MRO" => "Mauritanian Ouguiya",
                "MUR" => "Mauritian Rupee",
                "MXN" => "Mexican Peso",
                "MDL" => "Moldovan Leu",
                "MNT" => "Mongolian Tugrik",
                "MAD" => "Moroccan Dirham",
                "MZM" => "Mozambican Metical",
                "MMK" => "Myanmar Kyat",
                "NAD" => "Namibian Dollar",
                "NPR" => "Nepalese Rupee",
                "ANG" => "Netherlands Antillean Guilder",
                "TWD" => "New Taiwan Dollar",
                "NZD" => "New Zealand Dollar",
                "NIO" => "Nicaraguan CÃ³rdoba",
                "NGN" => "Nigerian Naira",
                "KPW" => "North Korean Won",
                "NOK" => "Norwegian Krone",
                "OMR" => "Omani Rial",
                "PKR" => "Pakistani Rupee",
                "PAB" => "Panamanian Balboa",
                "PGK" => "Papua New Guinean Kina",
                "PYG" => "Paraguayan Guarani",
                "PEN" => "Peruvian Nuevo Sol",
                "PHP" => "Philippine Peso",
                "PLN" => "Polish Zloty",
                "QAR" => "Qatari Rial",
                "RON" => "Romanian Leu",
                "RUB" => "Russian Ruble",
                "RWF" => "Rwandan Franc",
                "SVC" => "Salvadoran ColÃ³n",
                "WST" => "Samoan Tala",
                "SAR" => "Saudi Riyal",
                "RSD" => "Serbian Dinar",
                "SCR" => "Seychellois Rupee",
                "SLL" => "Sierra Leonean Leone",
                "SGD" => "Singapore Dollar",
                "SKK" => "Slovak Koruna",
                "SBD" => "Solomon Islands Dollar",
                "SOS" => "Somali Shilling",
                "ZAR" => "South African Rand",
                "KRW" => "South Korean Won",
                "XDR" => "Special Drawing Rights",
                "LKR" => "Sri Lankan Rupee",
                "SHP" => "St. Helena Pound",
                "SDG" => "Sudanese Pound",
                "SRD" => "Surinamese Dollar",
                "SZL" => "Swazi Lilangeni",
                "SEK" => "Swedish Krona",
                "CHF" => "Swiss Franc",
                "SYP" => "Syrian Pound",
                "STD" => "São Tomé and Príncipe Dobra",
                "TJS" => "Tajikistani Somoni",
                "TZS" => "Tanzanian Shilling",
                "THB" => "Thai Baht",
                "TOP" => "Tongan pa'anga",
                "TTD" => "Trinidad & Tobago Dollar",
                "TND" => "Tunisian Dinar",
                "TRY" => "Turkish Lira",
                "TMT" => "Turkmenistani Manat",
                "UGX" => "Ugandan Shilling",
                "UAH" => "Ukrainian Hryvnia",
                "AED" => "United Arab Emirates Dirham",
                "UYU" => "Uruguayan Peso",
                "USD" => "US Dollar",
                "UZS" => "Uzbekistan Som",
                "VUV" => "Vanuatu Vatu",
                "VEF" => "Venezuelan BolÃvar",
                "VND" => "Vietnamese Dong",
                "YER" => "Yemeni Rial",
                "ZMK" => "Zambian Kwacha"
            );
 

            return $currency_list;
    }

}

if (! function_exists('getStyleColor')) {
    /**
     * Get the primary color for a style
     * 
     * @param string $style The style name (style-one, style-two, etc.)
     * @return array Color information including hex code and name
     */
    function getStyleColor($style)
    {
        $styles = [
            'style-one' => [
                'hex' => '#ff6b6b',
                'name' => 'Coral Red',
                'description' => 'Coral Red (#ff6b6b)'
            ],
            'style-two' => [
                'hex' => '#0072ff',
                'name' => 'Blue',
                'description' => 'Blue (#0072ff)'
            ],
            'style-three' => [
                'hex' => '#FD841F',
                'name' => 'Orange',
                'description' => 'Orange (#FD841F)'
            ],
            'style-four' => [
                'hex' => '#76b852',
                'name' => 'Green',
                'description' => 'Green (#76b852)'
            ],
            'style-five' => [
                'hex' => '#34A293',
                'name' => 'Teal',
                'description' => 'Teal (#34A293)'
            ],
            'style-six' => [
                'hex' => '#7743DB',
                'name' => 'Purple',
                'description' => 'Purple (#7743DB)'
            ],
        ];
        
        return $styles[$style] ?? [
            'hex' => '#333f57',
            'name' => 'Default',
            'description' => 'Default'
        ];
    }
}