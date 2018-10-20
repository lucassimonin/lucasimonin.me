var gulpif = require('gulp-if');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var livereload = require('gulp-livereload');
var gulp = require('gulp');
var env = 'dev';

var adminRootPath = 'public/build/admin/';
var appRootPath = 'public/build/front/';

var paths = {
    admin: {
        js: [
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/bootstrap/dist/js/bootstrap.min.js',
            'node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js',
            'node_modules/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js',
            'assets/admin/js/app.js'
        ],
        css: [
            'assets/admin/js/vendor/ckeditor/contents.css',
            'node_modules/bootstrap/dist/css/bootstrap.css',
            'node_modules/flag-icon-css/css/flag-icon.min.css',
            'assets/admin/css/vendor/pixeladmin.min.css',
            'assets/admin/css/vendor/widgets.min.css',
            'assets/admin/css/vendor/themes/white.min.css',
            'assets/admin/css/app.css',
            'node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css',
            'node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.css',
        ],
        img: [
            'assets/admin/images/**'
        ],
        ckeditor: [
            'assets/admin/js/vendor/ckeditor/**'
        ],
        flags: [
            'node_modules/flag-icon-css/flags/**'
        ],
        fonts: [
            'assets/admin/fonts/**'
        ]
    },
    app: {
        js_head: [
            'web/assets/vendor/modernizr/modernizr.js',
            'node_modules/lazysizes/lazysizes.min.js'
        ],
        js: [
            'node_modules/jquery/dist/jquery.min.js',
            'node_modules/jquery.easing/js/jquery.easing.min.js',
            'node_modules/bootstrap/dist/js/bootstrap.min.js',
            'node_modules/scrollreveal/dist/scrollreveal.min.js',
            'node_modules/wowjs/dist/wow.min.js',
            'assets/front/js/script.js'
        ],
        js_ie: [
            'node_modules/html5shiv/dist/html5shiv.js'
        ],
        css: [
            'node_modules/bootstrap/dist/css/bootstrap.css',
            'node_modules/font-awesome/css/font-awesome.min.css',
            'node_modules/flag-icon-css/css/flag-icon.min.css',
            'assets/front/css/main.css',
            'node_modules/wowjs/css/libs/animate.css'
        ],
        css_print: [
            'node_modules/bootstrap/dist/css/bootstrap.css',
            'node_modules/font-awesome/css/font-awesome.min.css',
            'node_modules/flag-icon-css/css/flag-icon.min.css',
            'assets/front/css/print.css'
        ],
        fonts: [
            'assets/front/fonts/**',
            'node_modules/font-awesome/fonts/**',
            'node_modules/bootstrap/fonts/**'
        ],
        flags: [
            'node_modules/flag-icon-css/flags/**'
        ]
    }
};

gulp.task('admin-js', function () {
    return gulp.src(paths.admin.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(adminRootPath + 'js/'))
        ;
});

gulp.task('admin-css', function() {
    return gulp.src(paths.admin.css)
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(adminRootPath + 'css/'))
        ;
});

gulp.task('admin-ckeditor', function() {
    return gulp.src(paths.admin.ckeditor)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(adminRootPath + 'ckeditor/'))
        ;
});

gulp.task('admin-flags', function() {
    return gulp.src(paths.admin.flags)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(adminRootPath + 'flags/'))
        ;
});

gulp.task('admin-fonts', function() {
    return gulp.src(paths.admin.fonts)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(adminRootPath + 'fonts/'))
        ;
});

gulp.task('app-js', function () {
    return gulp.src(paths.app.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'js/'))
        ;
});

gulp.task('app-js-head', function () {
    return gulp.src(paths.app.js_head)
        .pipe(concat('app_head.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'js/'))
        ;
});

gulp.task('app-js-ie', function () {
    return gulp.src(paths.app.js_head)
        .pipe(concat('app_ie.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'js/'))
        ;
});

gulp.task('app-css', function() {
    return gulp.src(paths.app.css)
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'css/'))
        ;
});

gulp.task('app-css-print', function() {
    return gulp.src(paths.app.css_print)
        .pipe(concat('print.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'css/'))
        ;
});

gulp.task('app-img', function() {
    return gulp.src(paths.app.img)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'img/'))
        ;
});
gulp.task('app-fonts', function() {
    return gulp.src(paths.app.fonts)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'fonts/'))
        ;
});

gulp.task('app-flags', function() {
    return gulp.src(paths.app.flags)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'flags/'))
        ;
});


gulp.task('app-watch', function() {
    livereload.listen();

    gulp.watch(paths.admin.js, ['admin-js']);
    gulp.watch(paths.admin.css, ['admin-css']);
    gulp.watch(paths.admin.ckeditor, ['admin-ckeditor']);
    gulp.watch(paths.admin.flags, ['admin-flags']);
    gulp.watch(paths.admin.jsbuilder, ['admin-builder']);
    gulp.watch(paths.admin.fonts, ['admin-fonts']);

    gulp.watch(paths.admin.fonts, ['app-flags']);
    gulp.watch(paths.admin.fonts, ['app-fonts']);
    gulp.watch(paths.admin.fonts, ['app-img']);
    gulp.watch(paths.admin.fonts, ['app-css-print']);
    gulp.watch(paths.admin.fonts, ['app-css']);
    gulp.watch(paths.admin.fonts, ['app-js-head']);
    gulp.watch(paths.admin.fonts, ['app-js']);
    gulp.watch(paths.admin.fonts, ['app-js-ie']);
});

gulp.task('build', ['admin-js', 'admin-css', 'admin-ckeditor', 'admin-flags', 'admin-fonts', 'app-flags', 'app-fonts', 'app-css-print', 'app-css', 'app-js-head', 'app-js', 'app-js-ie']);
gulp.task('watch', ['build', 'app-watch']);
