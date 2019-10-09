<?php
/**
 * @var string $platoOrgID
 */
?>

Map

<div id="world-map"></div>
<script>
	jQuery(document).ready(function ($) {
		var colorRange = {
			continent: ['#ffe1cc', '#ff892c'],
			country:['#fad0b1', '#ff892c'],
		};
		var enabledCountries = {
			'CR': {plato: false, url: ''},
			'DE': {plato: true,},
			'EC': {plato: true,},
			'FI': {plato: true,},
			'FR': {plato: true,},
			'GB': {plato: true,},
			'ID': {plato: true,},
			'IS': {plato: true,},
			'IN': {plato: true,},
			'IT': {plato: true,},
			'KE': {plato: true,},
			'KH': {plato: true,},
			'JP': {plato: true,},
			'NP': {plato: true,},
			'RU': {plato: true,},
			'LK': {plato: true,},
			'TW': {plato: true,},
			'TH': {plato: true,},
			'TR': {plato: true,},
			// 'TG': {plato: false, url: ''},
			'TZ': {plato: true,},
			'VN': {plato: true,},
			'MX': {plato: true,},
			// 'ZW': {plato: false, url: ''},
		};
		initContinentMap('#world-map', '<?= $platoOrgID ?>', colorRange, enabledCountries);
	});
</script>