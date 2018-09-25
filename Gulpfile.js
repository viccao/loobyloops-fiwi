'use strict';

// Node/Gulp plugins
const gulp    = require('gulp');
const merge   = require('merge-stream');
const plugins = require('gulp-load-plugins')({ camelize: true });
const through = require('through2');
const browserSync = require('browser-sync').create();
const sourcemaps = require('gulp-sourcemaps');
const jsImport = require('gulp-js-import');
//const emojiText = require("emoji-text");

// browser-sync task for starting the server.
gulp.task('browser-sync', function() {
    //watch files
    var files = [
    './dist/css/styles.min.css',
    './*.php'
    ];

    //initialize browsersync
    browserSync.init(files, {
    //browsersync with a php server
    proxy: "http://lobbyloops.local/",
    notify: false
    });
});



// CSS task
gulp.task('styles', () => {
  return gulp.src('src/scss/main.scss')
    .pipe(plugins.plumber())
    .pipe(plugins.sass({ outputStyle: 'compressed' }))
    .pipe(plugins.postcss([
      require('autoprefixer')({ browsers: ['last 2 versions'] })
    ]))
    .pipe(plugins.rename('styles.min.css'))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(plugins.plumber.stop())
    .pipe(gulp.dest('dist/css'))
    .pipe(plugins.size({ title: 'styles' }));
});

gulp.task('modernizr', () => {
  return gulp.src('src/js/**/*.js')
    .pipe(plugins.modernizr())
    .pipe(plugins.concat('modernizr.min.js'))
    .pipe(plugins.uglify())
    .pipe(gulp.dest("dist/js/vendor/"))
});

// Fonts
gulp.task('fonts', () => {
  return gulp.src('src/fonts/**/*')
  .pipe(gulp.dest('dist/fonts'))
})

// Admin CSS task
gulp.task('admin-styles', () => {
  return gulp.src('admin/scss/main.scss')
    .pipe(sourcemaps.init())
    .pipe(plugins.plumber())
    .pipe(plugins.sass({
      outputStyle: 'compressed' }))
    .pipe(plugins.postcss([
      require('autoprefixer')({ browsers: ['last 2 versions', 'ie >= 9', 'and_chr >= 2.3'] })
    ]))
    .pipe(plugins.rename('styles.min.css'))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(plugins.plumber.stop())
    .pipe(gulp.dest('admin/css'))
    .pipe(plugins.size({ title: 'admin-styles' }));
});

// Scripts task
gulp.task('scripts', () => {
  return gulp.src([
      'src/js/**/*.js',
      '!src/js/vendor/*.js'
    ])
    .pipe(jsImport({hideConsole: true}))
    .pipe(plugins.plumber())
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.babel())
    .pipe(plugins.concat('scripts.min.js'))
    .pipe(plugins.uglify())
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(plugins.plumber.stop())
    .pipe(gulp.dest('dist/js'))
    .pipe(plugins.size({ title: 'scripts' }));
})

// Optimizes images
gulp.task('images', () => {
  return gulp.src('src/img/**/*')
    .pipe(plugins.plumber())
    .pipe(plugins.imagemin({
      progressive: true,
      svgoPlugins: [{removeViewBox: false}],
      use: [require('imagemin-pngquant')()]
    }))
    .pipe(plugins.plumber.stop())
    .pipe(gulp.dest('dist/img'))
    .pipe(plugins.size({ title: 'images' }));
});

// Build task
gulp.task('build', ['styles', 'admin-styles', 'browser-sync', 'scripts', 'modernizr', 'images', 'fonts']);
gulp.task('prod', ['styles', 'admin-styles', 'scripts', 'modernizr', 'images', 'fonts']);

// Watch task
gulp.task('watch', () => {
  gulp.watch(['src/img/**/*'], ['images']);
  gulp.watch(['src/fonts/**/*'], ['fonts']);
  gulp.watch(['src/scss/**/*.scss'], ['styles']);
  gulp.watch(['admin/scss/*.scss'], ['admin-styles', 'modernizr']);
  gulp.watch(['src/js/**/*.js'], ['scripts']);
});


//
//var uncss = require('gulp-uncss');
//var rename = require('gulp-rename');
//
//gulp.task('uncss', function () {
//
//    gulp.src('dist/css/styles.min.css')
//        .pipe(uncss({
//    ignore: [
//        /\.menu-open/,
//        /\.active/,
//        /\.invalid/,
//        /\.wpcf7/,
//    ],
//        html: ["http:\/\/eistein.esas.dev","http:\/\/eistein.esas.dev\/contact\/","http:\/\/eistein.esas.dev\/properties\/waxahachie-residential\/","http:\/\/eistein.esas.dev\/properties\/heartland-residential\/","http:\/\/eistein.esas.dev\/properties\/port-imperial-new-jersey\/","http:\/\/eistein.esas.dev\/properties\/blue-roseorlando-tourist-corridor-land\/","http:\/\/eistein.esas.dev\/properties\/west-valley-logistics-center\/","http:\/\/eistein.esas.dev\/properties\/victory-land-block-gblock-kblock-d\/","http:\/\/eistein.esas.dev\/properties\/vista-apartments\/","http:\/\/eistein.esas.dev\/properties\/arlington-downs-residential\/","http:\/\/eistein.esas.dev\/properties\/belz-factory-outlet-world-mall-2-las-vegas\/","http:\/\/eistein.esas.dev\/properties\/belz-factory-outlet-world-mall-1-las-vegas\/","http:\/\/eistein.esas.dev\/properties\/arapahoe-crossings-denver\/","http:\/\/eistein.esas.dev\/properties\/the-center-at-preston-ridge\/","http:\/\/eistein.esas.dev\/properties\/victory-park-retail\/","http:\/\/eistein.esas.dev\/team\/","http:\/\/eistein.esas.dev\/team-member\/pete-gill\/","http:\/\/eistein.esas.dev\/team-member\/lothar-estein\/","http:\/\/eistein.esas.dev\/team-member\/lance-fair\/","http:\/\/eistein.esas.dev\/team-member\/anthony-marcus\/","http:\/\/eistein.esas.dev\/properties\/","http:\/\/eistein.esas.dev\/active-developments\/","http:\/\/eistein.esas.dev\/about-us\/","http:\/\/eistein.esas.dev\/properties\/the-crest-at-las-colinas-apartments\/","http:\/\/eistein.esas.dev\/properties\/citicorp-center-chicago\/","http:\/\/eistein.esas.dev\/properties\/citadel-center\/","http:\/\/eistein.esas.dev\/properties\/one-victory-park\/","http:\/\/eistein.esas.dev\/properties\/victory-plaza-office-buildings\/","http:\/\/eistein.esas.dev\/properties\/the-peabody-orlando\/","http:\/\/eistein.esas.dev\/properties\/jw-marriott-chicago\/","http:\/\/eistein.esas.dev\/properties\/w-dallas-victory-hotel\/","http:\/\/eistein.esas.dev\/hello-world\/","http:\/\/eistein.esas.dev\/","http:\/\/eistein.esas.dev\/author\/admin\/","http:\/\/eistein.esas.dev\/category\/hotel\/","http:\/\/eistein.esas.dev\/category\/land\/","http:\/\/eistein.esas.dev\/category\/multi-family\/","http:\/\/eistein.esas.dev\/category\/office\/","http:\/\/eistein.esas.dev\/category\/retail\/","http:\/\/eistein.esas.dev\/category\/single-family-lot-development\/","http:\/\/eistein.esas.dev\/category\/uncategorized\/","http:\/\/eistein.esas.dev\/2017\/01\/","http:\/\/eistein.esas.dev\/?s=.","http:\/\/eistein.esas.dev\/?s=asdfasdfasdfasdf","http:\/\/eistein.esas.dev\/asdfasdfasdfasdf"]
//        })).pipe(rename({
//            suffix: '.clean'
//        }))
//
//    .pipe(gulp.dest('dist/css/'));
//
//});
//
//// Default task w UNCSS
//gulp.task('default', ['build', 'watch', 'uncss']);

// Default task
gulp.task('default', ['build', 'watch']);
gulp.task('prod', ['build', 'watch']);
