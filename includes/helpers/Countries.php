<?php

namespace VolunteersGuide;

class Countries
{
	static private $mapContinents = ['AF', 'NA', 'OC', 'AS', 'EU', 'SA',];
	static private $mapCountries = [
		'AE', 'AF', 'AL', 'AO', 'AG', 'AR', 'AM', 'AU', 'AT', 'AZ', 'BS', 'BH', 'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BT', 'BO',
		'BA', 'BW', 'BR', 'BN', 'BG', 'BF', 'BI', 'CM', 'CA', 'CV', 'CF', 'CH', 'CL', 'CN', 'CO', 'CD', 'CG', 'CR', 'CI', 'HR',
		'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'DZ', 'EC', 'EG', 'ER', 'ES', 'EE', 'EH', 'ET', 'FJ', 'FI', 'GF', 'FR', 'GA', 'GB',
		'GM', 'GE', 'DE', 'GH', 'GR', 'GD', 'GT', 'GN', 'GW', 'GY', 'HT', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE',
		'IL', 'IT', 'JM', 'JP', 'JO', 'KH', 'KM', 'KN', 'KZ', 'KE', 'KI', 'KR', 'KW', 'KG', 'LA', 'LC', 'LV', 'LB', 'LS', 'LR',
		'LY', 'LT', 'LK', 'LU', 'MK', 'MG', 'MW', 'MY', 'MV', 'ML', 'MT', 'MR', 'MU', 'MX', 'MD', 'MN', 'ME', 'MA', 'MZ', 'MM',
		'NA', 'NP', 'NL', 'NZ', 'NI', 'NE', 'NG', 'NO', 'OM', 'PK', 'PA', 'PG', 'PY', 'PE', 'PH', 'PL', 'PT', 'QA', 'RO', 'RU',
		'RW', 'WS', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SK', 'SI', 'SB', 'SV', 'SS', 'GQ', 'ZA', 'VC', 'SD', 'SR', 'SZ',
		'SE', 'SY', 'TD', 'TW', 'TJ', 'TZ', 'TH', 'TL', 'TG', 'TO', 'TT', 'TN', 'TR', 'TM', 'UG', 'UA', 'US', 'UY', 'UZ', 'VU',
		'VE', 'VN', 'YE', 'ZM', 'ZW',
	];
	static private $countryLongCodes = [
		'AD' => 'AND', 'AE' => 'ARE', 'AF' => 'AFG', 'AG' => 'ATG', 'AI' => 'AIA', 'AL' => 'ALB', 'AM' => 'ARM', 'AO' => 'AGO',
		'AQ' => 'ATA', 'AR' => 'ARG', 'AS' => 'ASM', 'AT' => 'AUT', 'AU' => 'AUS', 'AW' => 'ABW', 'AX' => 'ALA', 'AZ' => 'AZE',
		'BA' => 'BIH', 'BB' => 'BRB', 'BD' => 'BGD', 'BE' => 'BEL', 'BF' => 'BFA', 'BG' => 'BGR', 'BH' => 'BHR', 'BI' => 'BDI',
		'BJ' => 'BEN', 'BL' => 'BLM', 'BM' => 'BMU', 'BN' => 'BRN', 'BO' => 'BOL', 'BQ' => 'BES', 'BR' => 'BRA', 'BS' => 'BHS',
		'BT' => 'BTN', 'BV' => 'BVT', 'BW' => 'BWA', 'BY' => 'BLR', 'BZ' => 'BLZ', 'CA' => 'CAN', 'CC' => 'CCK', 'CD' => 'COD',
		'CF' => 'CAF', 'CG' => 'COG', 'CH' => 'CHE', 'CI' => 'CIV', 'CK' => 'COK', 'CL' => 'CHL', 'CM' => 'CMR', 'CN' => 'CHN',
		'CO' => 'COL', 'CR' => 'CRI', 'CU' => 'CUB', 'CV' => 'CPV', 'CW' => 'CUW', 'CX' => 'CXR', 'CY' => 'CYP', 'CZ' => 'CZE',
		'DE' => 'DEU', 'DJ' => 'DJI', 'DK' => 'DNK', 'DM' => 'DMA', 'DO' => 'DOM', 'DZ' => 'DZA', 'EC' => 'ECU', 'EE' => 'EST',
		'EG' => 'EGY', 'EH' => 'ESH', 'ER' => 'ERI', 'ES' => 'ESP', 'ET' => 'ETH', 'FI' => 'FIN', 'FJ' => 'FJI', 'FK' => 'FLK',
		'FM' => 'FSM', 'FO' => 'FRO', 'FR' => 'FRA', 'GA' => 'GAB', 'GB' => 'GBR', 'GD' => 'GRD', 'GE' => 'GEO', 'GF' => 'GUF',
		'GG' => 'GGY', 'GH' => 'GHA', 'GI' => 'GIB', 'GL' => 'GRL', 'GM' => 'GMB', 'GN' => 'GIN', 'GP' => 'GLP', 'GQ' => 'GNQ',
		'GR' => 'GRC', 'GS' => 'SGS', 'GT' => 'GTM', 'GU' => 'GUM', 'GW' => 'GNB', 'GY' => 'GUY', 'HK' => 'HKG', 'HM' => 'HMD',
		'HN' => 'HND', 'HR' => 'HRV', 'HT' => 'HTI', 'HU' => 'HUN', 'ID' => 'IDN', 'IE' => 'IRL', 'IL' => 'ISR', 'IM' => 'IMN',
		'IN' => 'IND', 'IO' => 'IOT', 'IQ' => 'IRQ', 'IR' => 'IRN', 'IS' => 'ISL', 'IT' => 'ITA', 'JE' => 'JEY', 'JM' => 'JAM',
		'JO' => 'JOR', 'JP' => 'JPN', 'KE' => 'KEN', 'KG' => 'KGZ', 'KH' => 'KHM', 'KI' => 'KIR', 'KM' => 'COM', 'KN' => 'KNA',
		'KP' => 'PRK', 'KR' => 'KOR', 'KW' => 'KWT', 'KY' => 'CYM', 'KZ' => 'KAZ', 'LA' => 'LAO', 'LB' => 'LBN', 'LC' => 'LCA',
		'LI' => 'LIE', 'LK' => 'LKA', 'LR' => 'LBR', 'LS' => 'LSO', 'LT' => 'LTU', 'LU' => 'LUX', 'LV' => 'LVA', 'LY' => 'LBY',
		'MA' => 'MAR', 'MC' => 'MCO', 'MD' => 'MDA', 'ME' => 'MNE', 'MF' => 'MAF', 'MG' => 'MDG', 'MH' => 'MHL', 'MK' => 'MKD',
		'ML' => 'MLI', 'MM' => 'MMR', 'MN' => 'MNG', 'MO' => 'MAC', 'MP' => 'MNP', 'MQ' => 'MTQ', 'MR' => 'MRT', 'MS' => 'MSR',
		'MT' => 'MLT', 'MU' => 'MUS', 'MV' => 'MDV', 'MW' => 'MWI', 'MX' => 'MEX', 'MY' => 'MYS', 'MZ' => 'MOZ', 'NA' => 'NAM',
		'NC' => 'NCL', 'NE' => 'NER', 'NF' => 'NFK', 'NG' => 'NGA', 'NI' => 'NIC', 'NL' => 'NLD', 'NO' => 'NOR', 'NP' => 'NPL',
		'NR' => 'NRU', 'NU' => 'NIU', 'NZ' => 'NZL', 'OM' => 'OMN', 'PA' => 'PAN', 'PE' => 'PER', 'PF' => 'PYF', 'PG' => 'PNG',
		'PH' => 'PHL', 'PK' => 'PAK', 'PL' => 'POL', 'PM' => 'SPM', 'PN' => 'PCN', 'PR' => 'PRI', 'PS' => 'PSE', 'PT' => 'PRT',
		'PW' => 'PLW', 'PY' => 'PRY', 'QA' => 'QAT', 'RE' => 'REU', 'RO' => 'ROU', 'RS' => 'SRB', 'RU' => 'RUS', 'RW' => 'RWA',
		'SA' => 'SAU', 'SB' => 'SLB', 'SC' => 'SYC', 'SD' => 'SDN', 'SE' => 'SWE', 'SG' => 'SGP', 'SH' => 'SHN', 'SI' => 'SVN',
		'SJ' => 'SJM', 'SK' => 'SVK', 'SL' => 'SLE', 'SM' => 'SMR', 'SN' => 'SEN', 'SO' => 'SOM', 'SR' => 'SUR', 'SS' => 'SSD',
		'ST' => 'STP', 'SV' => 'SLV', 'SX' => 'SXM', 'SY' => 'SYR', 'SZ' => 'SWZ', 'TC' => 'TCA', 'TD' => 'TCD', 'TF' => 'ATF',
		'TG' => 'TGO', 'TH' => 'THA', 'TJ' => 'TJK', 'TK' => 'TKL', 'TL' => 'TLS', 'TM' => 'TKM', 'TN' => 'TUN', 'TO' => 'TON',
		'TR' => 'TUR', 'TT' => 'TTO', 'TV' => 'TUV', 'TW' => 'TWN', 'TZ' => 'TZA', 'UA' => 'UKR', 'UG' => 'UGA', 'UM' => 'UMI',
		'US' => 'USA', 'UY' => 'URY', 'UZ' => 'UZB', 'VA' => 'VAT', 'VC' => 'VCT', 'VE' => 'VEN', 'VG' => 'VGB', 'VI' => 'VIR',
		'VN' => 'VNM', 'VU' => 'VUT', 'WF' => 'WLF', 'WS' => 'WSM', 'YE' => 'YEM', 'YT' => 'MYT', 'ZA' => 'ZAF', 'ZM' => 'ZMB',
		'ZW' => 'ZWE',
	];
	static private $continentNames = [
		'AF' => 'Africa', 'NA' => 'North America', 'OC' => 'Oceania', 'AS' => 'Asia', 'EU' => 'Europe', 'SA' => 'South America',
	];
	static private $countryNames = [];

	public static function getAllMapContinents()
	{
		return self::$mapContinents;
	}

	public static function getAllMapCountries()
	{
		return self::$mapCountries;
	}

	public static function getContinentName($code)
	{
		return isset(self::$continentNames[$code]) ? self::$continentNames[$code] : null;
	}

	public static function getCountryName($code, $locale = null)
	{
		$countryNames = self::getCountryNames('php', $locale);
		return isset($countryNames[$code]) ? $countryNames[$code] : null;
	}

	public static function getCountryLongCode($code)
	{
		return isset(self::$countryLongCodes[$code]) ? self::$countryLongCodes[$code] : null;
	}

	public static function getCountryNames($format = 'php', $locale = null, $echo = null)
	{
		$locale = $locale ?: Infos::getUserLanguage(true);
		if (isset(self::$countryNames[$format][$locale])) {
			return self::$countryNames[$format][$locale];
		}
		//$availableFormats = ['csv', 'html', 'json', 'mysql.sql', 'php', 'postgresql.sql', 'sqlite.sql', 'txt', 'xliff', 'xml', 'yaml',];
		$availableFormats = ['php',];
		if (!in_array($format, $availableFormats)) {
			Core::showError('getCountries: invalid format "' . $format . '" was requested', 500);
		}
		if ($echo === null) {
			$echo = ($format === 'json');
		}
		$ds      = DIRECTORY_SEPARATOR;
		$dirBase = Infos::getPluginDir() . 'libs' . $ds . 'composer' . $ds . 'umpirsky' . $ds . 'country-list' . $ds . 'data' . $ds;
		$dir     = $dirBase . $locale;
		if (!is_dir($dir)) {
			$dir = $dirBase . Config::getValue('default_language');
		}
		$path = $dir . $ds . 'country.' . $format;
		if ($echo) {
			readfile($path);
		} else {
			$countryNames                = include $path;
			self::$countryNames[$format][$locale] = $countryNames;
			return $countryNames;
		}
	}
}