module.exports = function(grunt) {

	grunt.initConfig({

		concat: {
			js: {
				options:	{ separator: ';' },
				src:		['public/js/src/*.js'],
				dest:		'public/js/all.js'
			}
		},

		uglify: {
			options: {
				mangle: true
			},
			js: {
				files: { 'public/js/all.js': ['public/js/all.js'] }
			}
		},

		cssmin: {
			options: {
				keepSpecialComments: '*'
			},
			combine: {
				files: {
					'public/css/dest/style.min.css': [
						'public/css/base.css'
					]
				}
			},
			mimify: {
				expand: true,
				cwd:	'public/css/dest/',
				src:	['style.min.css'],
				dest:	'public/css/dest/',
				ext:	'.min.css'
			}
		},

		clean: {
			options: {
				force: true
			},
			css: [''],
		},

		watch: {
			options: {
				livereload: true,
				spawn: false
			},
			other: {
				files: [
					'app/templates/**/*.twig',
					'app/*.php',
					'app/lib/*.php',
					'!app/vendor',
					'public/**/*.php',
					'!public/bower_components'
				]
			},
			js: {
				files: ['public/js/src/*.js'],
				tasks: ['concat:js', 'uglify:js']
			},
			css: {
				files: ['public/css/*.css'],
				tasks: ['cssmin']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask( 'default', ['watch'] );
};