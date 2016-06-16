module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            dist: {
                files: {
                    'stylesheets/css/application.css' : 'stylesheets/sass/application.sass'
                }
            }
        },
        cssmin: {
            minify: {
                src: 'stylesheets/css/application.css',
                dest: 'stylesheets/css/application.min.css'
            }
        },
        uglify: {
            target: {
                files: {
                    'v4/js/main.min.js': ['v4/js/dev/main.js'],
                    'v4/js/app.min.js': ['v4/js/dev/app.js'],
                    'v4/js/directives.min.js': ['v4/js/dev/directives/combobox.js'],
                    'v4/js/controllers.min.js': ['v4/js/dev/controllers/navigation-controller.js',
                                                'v4/js/dev/controllers/browser-controller.js']
                }
            }
        },
        watch: {
            css: {
                files: '**/*.sass',
                tasks: ['sass', 'cssmin']
            },
            main_scripts: {
                files: '**/dev/*.js',
                tasks: ['uglify']
            },
            controllers: {
                files: '**/dev/controllers/*.js',
                tasks: ['uglify']
            },
            directives: {
                files: '**/dev/directives/*.js',
                tasks: ['uglify']
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default',['watch']);
};