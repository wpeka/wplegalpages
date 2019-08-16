module.exports = function (grunt) {

	'use strict';

	// Project configuration
	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),
		clean: {
			build: ['release/<%= pkg.version %>']
		},
		copy: {
			build: {
				options: {
					mode: true,
					expand: true,
				},
				src: [
					'**',
					'!node_modules/**',
					'!vendor/**',
					'!release/**',
					'!build/**',
					'!.git/**',
					'!Gruntfile.js',
					'!package.json',
					'!package-lock.json',
					'!.gitignore',
					'!.gitmodules',
					'!composer.lock',
					'!composer.json'
				],
				dest: 'release/<%= pkg.version %>/'
			}
		},
		compress: {
			build: {
				options: {
					mode: 'zip',
					archive: './release/<%= pkg.name %>.<%= pkg.version %>.zip'
				},
				expand: true,
				cwd: 'release/<%= pkg.version %>/',
				src: ['**/*'],
				dest: '<%= pkg.name %>'
			}
		},

		addtextdomain: {
			options: {
				textdomain: 'wplegalpages',
			},
			update_all_domains: {
				options: {
					updateDomains: true
				},
				src: ['*.php', '**/*.php', '!\.git/**/*', '!bin/**/*', '!node_modules/**/*', '!tests/**/*', '!vendor/**/*']
			}
		},

		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},

		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					exclude: ['\.git/*', 'bin/*', 'node_modules/*', 'tests/*', '!vendor/**/*'],
					mainFile: 'wplegalpages.php',
					potFilename: 'wplegalpages.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true
					},
					type: 'wp-plugin',
					updateTimestamp: true
				}
			}
		},
	});

	grunt.loadNpmTasks('grunt-wp-i18n');
	grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.registerTask('default', ['i18n', 'readme']);
	grunt.registerTask('i18n', ['addtextdomain', 'makepot']);
	grunt.registerTask('readme', ['wp_readme_to_markdown']);
	grunt.registerTask('build', ['clean:build', 'copy:build', 'compress:build']);

	grunt.util.linefeed = '\n';

};
