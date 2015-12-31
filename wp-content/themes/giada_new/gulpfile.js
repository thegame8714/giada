'use strict';

var gulp = require('gulp'),
    less = require('gulp-less'),
    minifyCss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify');


gulp.task('less', function() {
    return gulp.src('./assets/less/*.less')
        .pipe(less())
        .pipe(minifyCss())
        .pipe(gulp.dest('./dist/css/'));
});

gulp.task('jshint', function() {
    return gulp.src('./assts/js/*.js')
        .pipe(jshint())
        .pipe(uglify())
        .pipe(gulp.dest('./dist/js'));
});

gulp.task('copy', function() {
    gulp.src('./bower_components/bootstrap/less/*.less').pipe(gulp.dest('./assets/less/'));
    gulp.src('./bower_components/bootstrap/dist/js/bootstrap.min.js').pipe(gulp.dest('./assets/js/'));
});


gulp.task('default',['less','jshint']);

