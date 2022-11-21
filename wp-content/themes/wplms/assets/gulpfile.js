var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    gutil = require('gulp-util'),
    rimraf = require('gulp-rimraf');

gulp.task('get_select2',function() {
  return gulp.src('bower_components/select2/src/scss/core.scss',  { style: 'nested' })
        .on('error', sass.logError)
        .pipe(autoprefixer('last 2 version'))   
        .pipe(minifycss())    
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('css'));
});

gulp.task('remove-style', function() {
  return gulp.src('css/style.css', { read: false })
    .pipe(rimraf({ force: true }));
});

gulp.task('remove-styles',['remove-style'], function() {
  return gulp.src('css/*.min.css', { read: false })
    .pipe(rimraf({ force: true }));
});

gulp.task('bbpress-styles',['remove-styles'], function() {
  return sass('css/scss/bbpress.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS Styles task complete' }));
});

gulp.task('woocommerce-styles',['bbpress-styles'], function() {
  return sass('css/scss/woocommerce.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS Styles task complete' }));
});

gulp.task('dark-styles',['woocommerce-styles'], function() {
  return sass('css/scss/skins/dark.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS Material Styles task complete' }));
});

gulp.task('material-styles',['dark-styles'], function() {
  return sass('css/scss/skins/material.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS Material Styles task complete' }));
});

gulp.task('elegant-styles',['material-styles'], function() {
  return sass('css/scss/skins/elegant.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS Elegant Styles task complete' }));
});

gulp.task('minimal-styles',['elegant-styles'], function() {
  return sass('css/scss/skins/minimal.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS Minimal Styles task complete' }));
});

gulp.task('modern-styles',['minimal-styles'], function() {
  return sass('css/scss/skins/modern.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS Modern Styles task complete' }));
});

gulp.task('core-styles',['modern-styles'], function() {
  return sass('css/scss/core.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS core Styles task complete' }));
});

gulp.task('header-styles',['core-styles'], function() {
  return sass('css/scss/header.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))  
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))  
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS header task complete' }));
});
//'header-styles'
gulp.task('v4-styles',[], function() {
  return sass('css/scss/v4.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS v4 Styles task complete' }));
});

gulp.task('front-styles',['v4-styles'], function() {
  return sass('css/scss/style.scss', { style: 'nested' })
    .on('error', sass.logError)
    .pipe(autoprefixer('last 2 version'))    
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'WPLMS Styles task complete' }));
});


gulp.task('styles',['front-styles'], function() {
  return gulp.src(['css/style.css'])    
    .pipe(concat('style.css'))
    .pipe(minifycss())    
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css'))
    .pipe(notify({ message: 'Concatenation task complete' }));
});

gulp.task('remove-scripts', function() {
  return gulp.src('js/*.min.js', { read: false })
    .pipe(rimraf({ force: true }));
});

gulp.task('depdendency-scripts',['remove-scripts'], function() {
  return gulp.src(['bower_components/flexslider/jquery.flexslider.js',
    'js/old_files/jquery.fitvids.js',
    'js/old_files/jquery.isotope.min.js',
    'js/rating.js'])
    .pipe(uglify().on('error', gutil.log))
    .pipe(concat('wplms.jquery.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify().on('error', gutil.log))
    .pipe(gulp.dest('js'))
    .pipe(notify({ message: 'WPLMS Scripts task complete' }));
});

gulp.task('scripts',['depdendency-scripts'], function() {
  return gulp.src(['js/v4.js'])
    .pipe(uglify().on('error', gutil.log))
    .pipe(concat('wplms.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify().on('error', gutil.log))
    .pipe(gulp.dest('js'))
    .pipe(notify({ message: 'WPLMS Scripts task complete' }));
});


gulp.task('front', ['styles','scripts']);