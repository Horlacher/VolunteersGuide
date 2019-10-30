const pubCss = './public/css/',
	pubJs = './public/js/',
	adminCss = './admin/css/',
	adminJs = './admin/js/',
	buildComposer = './build/libs/composer/',
	buildCountryList = buildComposer + 'umpirsky/country-list/data/';
module.exports = function (grunt) {
	require('load-grunt-tasks')(grunt); // npm install --save-dev load-grunt-tasks
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		copy: {
			adminAssets: {
				files: [{
					expand: true,
					cwd: 'node_modules/chart.js/dist',
					src: '*.js',
					dest: adminJs,
					filter: 'isFile'
				}, {
					expand: true,
					cwd: 'node_modules/chart.js/dist',
					src: '*.css',
					dest: adminCss,
					filter: 'isFile'
				}],
			},
			buildDir: {
				files: [{
					expand: true,
					src: [
						'*.php', '*.txt',
						'public/**/**.php', 'public/**/**.min.js', 'public/**/**.min.css',
						'admin/**/**.php', 'admin/**/**.min.css', 'admin/**/**.min.js',
						'includes/**', 'languages/*.mo', 'libs/**',
					],
					dest: 'build/'
				}],
			},
			buildWpOrg: {
				files: [
					{
						expand: true,
						cwd: 'build/',
						src: '**',
						dest: 'buildWpOrg/trunk/'
					}, {
						expand: true,
						cwd: 'assets/',
						src: '**',
						dest: 'buildWpOrg/assets/'
					}
				],
			},
		},
		po2mo: {
			files: {
				src: 'languages/*.po',
				expand: true,
			},
		},
		mkdir: {
			build: {
				options: {
					create: ['build']
				},
			},
			buildWpOrg: {
				options: {
					create: ['buildWpOrg']
				},
			},
		},
		checkDependencies: {
			this: {},
		},
		babel: {
			options: {
				sourceMap: true,
				presets: ['@babel/preset-env',],
				plugins: [
					'@babel/plugin-proposal-class-properties',
				]
			},
			dist: {
				files: {
					[pubJs + 'volunG-public.es5.js']: pubJs + 'volunG-public.js',
				}
			}
		},
		uglify: {
			admin: {
				files: {
					[adminJs + 'volunG-admin.min.js']: [
						'./node_modules/jquery-wheelcolorpicker/jquery.wheelcolorpicker.js',
						adminJs + 'volunG-admin.js',
					]
				}
			},
			public: {
				files: {
					[pubJs + 'volunG-public.min.js']: [
						'./node_modules/jvectormap-next/jquery-jvectormap.min.js',
						pubJs + 'jquery-jvectormap-continents-mill-en.js',
						pubJs + 'jquery-jvectormap-world-mill-en.js',
						pubJs + 'countries.js',
						pubJs + 'volunG-public.es5.js',
					]
				}
			},
			publicDev: {
				files: {
					[pubJs + 'volunG-public.dep.js']: [
						'./node_modules/jvectormap-next/jquery-jvectormap.min.js',
						pubJs + 'jquery-jvectormap-continents-mill-en.js',
						pubJs + 'jquery-jvectormap-world-mill-en.js',
						pubJs + 'countries.js',
					]
				}
			}
		},
		sass: {
			dist: {
				options: {
					style: 'expanded'
				},
				files: {
					[pubCss + 'volunG-public.css']: pubCss + 'volunG-public.scss',
					[adminCss + 'volunG-admin.css']: adminCss + 'volunG-admin.scss',
				},
			}
		},
		cssmin: {
			options: {
				mergeIntoShorthands: false,
				roundingPrecision: -1
			},
			target: {
				files: {
					[pubCss + 'volunG-public.min.css']: [
						'./node_modules/jvectormap-next/jquery-jvectormap.css',
						pubCss + 'volunG-public.css',
					],
					[adminCss + 'volunG-admin.min.css']: [
						'./node_modules/jquery-wheelcolorpicker/css/wheelcolorpicker.css',
						adminCss + 'volunG-admin.css',
					],
				}
			}
		},
		compress: {
			main: {
				options: {
					archive: '../VolunteersGuide.zip'
					/*
					archive: function () {
						// The global value git.tag is set by another task
						return 'volunG-' + git.tag + '.zip'
					}
					*/
				},
				expand: true,
				cwd: 'build/',
				src: ['**'],
				dest: '',
			}
		},
		clean: {
			build: ['build/',],
			buildComposer: [
				buildCountryList + '*_*', buildCountryList + '*/*.csv', buildCountryList + '*/*.html', buildCountryList + '*/*.json',
				buildCountryList + '*/*.sql', buildCountryList + '*/*.txt', buildCountryList + '*/*.xliff', buildCountryList + '*/*.xml',
				buildCountryList + '*/*.yaml',
			],
			buildWpOrg: ['buildWpOrg/',],
			public: [pubCss + '*.css', pubCss + '*.map', pubJs + '*.min.js', pubJs + '*.map',],
			admin: [adminCss + '*.css', adminCss + '*.map', adminJs + 'volunG-admin.min.js', adminJs + 'Chart.*',],
		},
		checkwpversion: {
			options: {
				//Options specifying location your plug-in's header and readme.txt
				readme: 'README.txt',
				plugin: 'VolunteersGuide.php',
			},
			check: { //Check plug-in version and stable tag match
				version1: 'plugin',
				version2: 'readme',
				compare: '==',
			},
			check2: { //Check plug-in version and package.json match
				version1: 'plugin',
				version2: '<%= pkg.version %>',
				compare: '==',
			},
		},
		availabletasks: {
			tasks: {}
		}
	});

	// Load NPM modules
	grunt.loadNpmTasks('grunt-available-tasks');
	grunt.loadNpmTasks('grunt-check-dependencies');

	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-checkwpversion');

	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-mkdir');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-po2mo');

	// define Tasks
	grunt.registerTask('default', 'availabletasks');
	grunt.registerTask('buildAssets', [
		'checkDependencies', 'clean', 'copy:adminAssets', 'babel', 'uglify:public', 'uglify:admin', 'sass', 'cssmin', 'po2mo',
	]);
	grunt.registerTask('buildAssetsDev', [
		'uglify:publicDev', 'uglify:admin', 'sass', 'cssmin', 'po2mo',
	]);
	grunt.registerTask('build', [
		'buildAssets', 'mkdir:build', 'copy:buildDir', 'clean:buildComposer',
	]);
	grunt.registerTask('buildZip', [
		'build', 'compress', 'clean:build',
	]);
	grunt.registerTask('buildWpOrg', [
		'build', 'copy:buildWpOrg', 'clean:build',
	]);
};
