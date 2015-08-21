module.exports = function (grunt) {
    var bannerContent = '/*!\n' +
        ' * <%= pkg.name %> - <%= pkg.description %>\n' +
        ' * Version: <%= modx.version %>\n' +
        ' * Build date: <%= grunt.template.today("yyyy-mm-dd") %>\n' +
        ' */\n';

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        modx: grunt.file.readJSON('_build/config.json'),
        uglify: {
            options: {
                banner: bannerContent
            },
            emo: {
                sourceMap: true,
                src: ['assets/components/emo/js/emo.js'],
                dest: 'assets/components/emo/js/emo.min.js'
            }
        },
        cssmin: {
            options: {
                banner: bannerContent
            },
            target: {
                src: ['assets/components/emo/css/emo.css'],
                dest: 'assets/components/emo/css/emo.min.css'
            }
        },
        watch: {
            scripts: {
                files: ['assets/components/emo/js/emo.js'],
                tasks: ['uglify']
            },
            css: {
                files: ['assets/components/emo/css/emo.css'],
                tasks: ['cssmin']
            }
        }
    });

    //load the packages
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    //register the task
    grunt.registerTask('default', ['uglify', 'cssmin']);
};