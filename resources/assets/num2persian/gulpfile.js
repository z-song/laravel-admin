var gulp = require('gulp');
var minify = require('gulp-minify');
var replace = require('gulp-replace');
const headerComment = require('gulp-header-comment');
var babel = require('gulp-babel');
gulp.task('default', function () {
  return gulp.src('src/num2persian.js')
    .pipe(replace('export default Num2persian', ''))
    .pipe(babel())
    .pipe(minify())
    .pipe(headerComment(`
            Name:Javascript Number To Persian Convertor.
            License: <%= pkg.license %>
            Generated on <%= moment().format('YYYY-MM-DD') %>
            Author:Mahmoud Eskanadri.
            Copyright:2018 http://Webafrooz.com.
            version:<%= pkg.version %>
            Email:info@webafrooz.com,sbs8@yahoo.com
            coded with ♥ in Webafrooz.
            big numbers refrence: https://fa.wikipedia.org/wiki/%D9%86%D8%A7%D9%85_%D8%A7%D8%B9%D8%AF%D8%A7%D8%AF_%D8%A8%D8%B2%D8%B1%DA%AF

          `))
    .pipe(gulp.dest('dist'));
});
