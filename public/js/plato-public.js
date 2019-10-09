var continentsZoom = {
	'AF': {
		x: 0.5,
		y: 0.65,
		scale: 2,
	},
	'NA': {
		x: 0.1,
		y: 0.37,
		scale: 2,
	},
	/*
	'OC':{
		x: 0.9,
		y: 0.9,
		scale: 3,
	},
	*/
	'AS': {
		x: 0.7,
		y: 0.48,
		scale: 2,
	},
	'EU': {
		x: 0.55,
		y: 0.1,
		scale: 2,
	},
	'SA': {
		x: 0.3,
		y: 0.8,
		scale: 2,
	},
};
var continentsColors = {
	'AF': 40,
	'NA': 100,
	'OC': 0,
	'AS': 100,
	'EU': 40,
	'SA': 40,
};
var mapCountries = [
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
var platoUrl = 'https://frontend.workcamp-plato.org/searchresult.352.aspx?platoorgid={platoOrgID}&countries={countries}';
var $map, enabledCountries = {}, colorRangeCountry;

function getCountryColors() {
	var countryColors = {};
	mapCountries.forEach(function (entry) {
		countryColors[entry] = (typeof enabledCountries[entry] === 'undefined') ? 0 : 10;
	});
	return countryColors;
}

function showCountry(code) {
	if (typeof enabledCountries[code] === 'undefined' && typeof allCountries[code] === 'undefined') {
		return;
	}
	var url = platoUrl.replace('{countries}', allCountries[code]['platoCode']);
	var win = window.open(url, '_blank');
	win.focus();
}

function switchMap(code) {
	if (typeof continentsZoom[code] === 'undefined') {
		return;
	}
	$map.find('.jvectormap-container').hide();
	var zoom = continentsZoom[code];
	$map.vectorMap({
		map: 'world_mill_en',
		focusOn: zoom,
		series: {
			regions: [{
				values: getCountryColors(),
				scale: colorRangeCountry,
				normalizeFunction: 'polynomial'
			}]
		},
		onRegionOver: function (e, code) {
			if (typeof enabledCountries[code] === 'undefined') {
				return;
			}
			$map.css('cursor', 'pointer');
		},
		onRegionOut: function (e, code) {
			$map.css('cursor', 'default');
		},
		onRegionClick: function (event, code) {
			showCountry(code);
		},
	});
}

function initContinentMap(mapSelector, platoOrgID, colorRange, enableCountries) {
	enabledCountries = enableCountries;
	platoUrl = platoUrl.replace('{platoOrgID}', platoOrgID);
	colorRangeCountry = colorRange.country;
	var colorRangeContinent = colorRange.continent;
	$map = jQuery(mapSelector);
	$map.vectorMap({
		map: 'continents_mill',
		series: {
			regions: [{
				values: continentsColors,
				scale: colorRangeContinent,
				normalizeFunction: 'polynomial',
			}]
		},
		hover: {
			fill: "#cc6d25"
		},
		onRegionTipShow: function (e, el, code) {
			//el.html(el.html());
		},
		onRegionOver: function (e, code) {
			if (typeof continentsZoom[code] === 'undefined') {
				return;
			}
			$map.css('cursor', 'pointer');
		},
		onRegionOut: function (e, code) {
			$map.css('cursor', 'default');
		},
		onRegionClick: function (event, code) {
			switchMap(code);
		}
	});
}