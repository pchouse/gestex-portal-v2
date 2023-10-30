const gulp = require("gulp");
const less = require('gulp-less');
const autoprefixer = require("gulp-autoprefixer");
const cleanCSS = require('gulp-clean-css');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const rename = require("gulp-rename");

gulp.task(
    "less",
    function (done) {
        gulp.src('./src/public_src/css/*.less')
        .pipe(less({compress: false})).on('error', (error) => console.log(error))
        .pipe(autoprefixer('last 10 versions', 'ie 9'))
        .pipe(cleanCSS({keepBreaks: true, colors: false}))
        .pipe(gulp.dest('./src/public/css'));
        done();
    }
);


gulp.task(
    "default",
    function (done) {

        done()

    }
);