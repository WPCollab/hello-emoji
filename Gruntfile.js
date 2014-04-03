module.exports = function (grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		cssmin: {
			options: {
				banner: '/* Hello Emoji <%= pkg.version %> - CSS */'
			},
			minify: {
				expand: true,
				cwd: 'css/',
				src: ['*.css', '!*.min.css'],
				dest: 'css/',
				ext: '.min.css'
			}
		},
		uglify: {
			options: {
				banner: '/*! Hello Emoji <%= pkg.version %> - JS */\n'
			},
			files: {
				src: 'js/hello-emoji.js',
				dest: 'js/',
				expand: true,
				flatten: true,
				ext: '.min.js'
			}
		},
		po2mo: {
			files: {
				src: 'languages/*.po',
				expand: true,
			},
		},
		watch: {
			js:  {
				files: 'js/hello-emoji.js',
				tasks: [ 'uglify' ]
			},
			cssmin: {
				files: 'css/hello-emoji.css',
				tasks: ['cssmin']
			},
			po2mo: {
				files: 'languages/*.po',
				tasks: ['po2mo']
			}
		},
		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					potFilename: 'hello-emoji.pot',
					processPot: function( pot, options ) {
						pot.headers['report-msgid-bugs-to'] = 'https://github.com/WPCollab/hello-emoji/issues\n';
						pot.headers['plural-forms'] = 'nplurals=2; plural=n != 1;';
						pot.headers['last-translator'] = 'Christian Foellmann <foellmann@foe-services.de>\n';
						pot.headers['language-team'] = 'WPCollab Team <info@wpcollab.co>\n';
						pot.headers['x-poedit-basepath'] = '.\n';
						pot.headers['x-poedit-language'] = 'English\n';
						pot.headers['x-poedit-country'] = 'UNITED STATES\n';
						pot.headers['x-poedit-sourcecharset'] = 'utf-8\n';
						pot.headers['x-poedit-keywordslist'] = '__;_e;__ngettext:1,2;_n:1,2;__ngettext_noop:1,2;_n_noop:1,2;_c,_nc:4c,1,2;_x:1,2c;_ex:1,2c;_nx:4c,1,2;_nx_noop:4c,1,2;\n';
						pot.headers['x-poedit-bookmarks'] = '\n';
						pot.headers['x-poedit-searchpath-0'] = '.\n';
						pot.headers['x-textdomain-support'] = 'yes\n';
						return pot;
					},
					type: 'wp-plugin'
				}
			}
		}
	});

	// load plugins
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin')
	grunt.loadNpmTasks('grunt-wp-i18n');
	grunt.loadNpmTasks('grunt-po2mo');

	// register at least this one task
	grunt.registerTask('default', [ 'watch' ]);
};