module.exports = function (grunt) {
    // Project configuration.
    grunt.initConfig({
        modx: grunt.file.readJSON('_build/config.json'),
        banner: '/*!\n' +
        ' * <%= modx.name %> - <%= modx.description %>\n' +
        ' * Version: <%= modx.version %>\n' +
        ' * Build date: <%= grunt.template.today("yyyy-mm-dd") %>\n' +
        ' */\n',
        usebanner: {
            dist: {
                options: {
                    position: 'top',
                    banner: '<%= banner %>'
                },
                files: {
                    src: [
                        'assets/components/emo/js/emo.min.js',
                        'assets/components/emo/css/emo.min.css'
                    ]
                }
            }
        },
        uglify: {
            emo: {
                sourceMap: true,
                src: ['assets/components/emo/js/emo.js'],
                dest: 'assets/components/emo/js/emo.min.js'
            }
        },
        cssmin: {
            emo: {
                src: ['assets/components/emo/css/emo.css'],
                dest: 'assets/components/emo/css/emo.min.css'
            }
        },
        watch: {
            scripts: {
                files: ['assets/components/emo/js/emo.js'],
                tasks: ['uglify', 'usebanner']
            },
            css: {
                files: ['assets/components/emo/css/emo.css'],
                tasks: ['cssmin', 'usebanner']
            }
        }
    });

    //load the packages
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-banner');

    //register the task
    grunt.registerTask('default', ['uglify', 'cssmin', 'usebanner']);
};