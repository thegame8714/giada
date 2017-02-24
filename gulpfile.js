var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('sass', function() {
    return gulp.src('./style/sass/*.scss')
                .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
                .pipe(gulp.dest('./style/css'));
});

gulp.task('sass:watch', function() {
    gulp.watch('./style/sass/*.scss', ['sass']);
});

gulp.task('default', function() {
    gulp.start('sass', 'sass:watch');
});