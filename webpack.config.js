const path = require('path');

module.exports = {
	entry: {
		public: ['./public/js/plato-public.js',],
		worldMap: ['./node_modules/jvectormap-next/jquery-jvectormap.min.js',],
	},
	output: {
		path: path.resolve(__dirname + '/public/js'),
		filename: 'plato-[name].min.js'
	},
	module: {
		rules: [
			{
				test: /\.css$/,
				use: ['style-loader', 'css-loader'],
			},
		],
	},
	externals: {
		// require("jquery") is external and available
		//  on the global var jQuery
		"jquery": "jQuery"
	},
	performance: {
		maxAssetSize: 1200000,
		maxEntrypointSize: 1200000,
	},
	mode: 'production',
};