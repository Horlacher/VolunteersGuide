class volunG_continentMap {
	$map;
	platoUrl;
	colors;
	countries;
	continents;
	continentsZoom = {
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
	mapContinents = ['AF', 'NA', 'OC', 'AS', 'EU', 'SA',];
	mapCountries = [
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

	constructor(language, ajaxUrl, config) {
		const self = this;
		this.$map = jQuery('#volunG-world-map');
		if (config) {
			this.initContinentMap(config);
		} else {
			jQuery.ajax({
					type: 'GET',
					url: ajaxUrl,
					data: {action: 'volunG_MapConfig', locale: language},
					dataType: 'json',
					success: (data) => {
						self.initContinentMap(data);
					}
				}
			);
		}
	}

	initContinentMap(config) {
		this.platoUrl = config.platoUrl;
		this.colors = config.colors;
		this.countries = config.countries;
		this.continents = config.continents;
		this.loadMapWorld();
	}

	getCountryColors() {
		const self = this;
		var countryColors = {};
		self.mapCountries.forEach(function (key) {
			countryColors[key] = (typeof self.countries[key] === 'undefined') ? 0 : self.countries[key].intensity;
		});
		return countryColors;
	}

	getContinentColors() {
		const self = this;
		var continentColors = {};
		self.mapContinents.forEach(function (key) {
			continentColors[key] = (typeof self.continents[key] === 'undefined') ? 0 : self.continents[key];
		});
		return continentColors;
	}

	showCountry(code) {
		if (this.countries[code] === undefined) {
			return;
		}
		var country = this.countries[code];

		if (country['plato'] !== undefined) {
			var url = this.platoUrl.replace('{countries}', country['plato']);
		} else if (country['url'] !== undefined) {
			var url = country['url'];
		} else {
			return;
		}
		var win = window.open(url, '_blank');
		win.focus();
	}

	loadMapContinent(code) {
		const self = this;
		if (self.continentsZoom[code] === undefined) {
			return;
		}
		self.$map.find('.jvectormap-container').hide();
		var zoom = self.continentsZoom[code];
		self.$map.vectorMap({
			map: 'world_mill_en',
			focusOn: zoom,
			series: {
				regions: [{
					values: self.getCountryColors(),
					scale: self.colors.country,
					normalizeFunction: 'polynomial'
				}]
			},
			hover: {
				fill: self.colors.continentHover
			},
			labels: {
				regions: {
					render: function (code) {
						if (self.countries[code] === undefined) {
							return '';
						}
						return self.countries[code].name;
					},
				}
			},
			onRegionOver: (e, code) => {
				if (self.countries[code] === undefined) {
					return;
				}
				self.$map.css('cursor', 'pointer');
			},
			onRegionTipShow: (e, el, code) => {
				if (self.countries[code] === undefined) {
					return;
				}
				el.html(self.countries[code].name);
			},
			onRegionOut: (e, code) => {
				self.$map.css('cursor', 'default');
			},
			onRegionClick: (event, code) => {
				self.showCountry(code);
			},
			zoomOnScroll: true,
			zoomButtons : true,
		});
	}

	loadMapWorld() {
		const self = this;
		self.$map.find('.loading').remove();
		self.$map.vectorMap({
			map: 'continents_mill',
			series: {
				regions: [{
					values: self.getContinentColors(),
					scale: self.colors.continent,
					normalizeFunction: 'polynomial',
				}]
			},
			hover: {
				fill: self.colors.countryHover
			},
			onRegionTipShow: (e, el, code) => {
				el.html(el.html());
			},
			onRegionOver: (e, code) => {
				if (self.continentsZoom[code] === undefined) {
					return;
				}
				self.$map.css('cursor', 'pointer');
			},
			onRegionOut: (e, code) => {
				self.$map.css('cursor', 'default');
			},
			onRegionClick: (event, code) => {
				self.loadMapContinent(code);
			},
			zoomOnScroll: false,
			zoomButtons : false,
		});
	}
}