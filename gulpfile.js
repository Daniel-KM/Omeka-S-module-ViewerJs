'use strict';

const del = require('del');
const gulp = require('gulp');
const lec = require('gulp-line-ending-corrector');
const replace = require('gulp-string-replace');
const filter = require('gulp-filter');

gulp.task('clean', function(done) {
    return del('asset/vendor/viewerjs');
});

gulp.task('sync', function (next) {
    const f = filter(['**/*.html', '**/*.js', '**/*.css'], {restore: true});
    gulp.src(['node_modules/node-viewerjs/release/**'])
    .pipe(f)
    .pipe(lec())
    .pipe(f.restore)
    .pipe(gulp.dest('asset/vendor/viewerjs/'))
    .on('end', next);
});

gulp.task('hack_viewerjs', function (done) {
    gulp.src(['node_modules/node-viewerjs/release/viewer.js'])
    .pipe(lec())
    .pipe(replace(/^        if \( documentUrl \) \{$/gm, '        if (! documentUrl ) { documentUrl = parameters.file; }' + "\n"
        + '        if ( documentUrl ) {'
    ))
    .pipe(gulp.dest('asset/vendor/viewerjs/'));

    gulp.src(['node_modules/node-viewerjs/release/index.html'])
    .pipe(lec())
    .pipe(replace(/^<script src="viewer.js"/gm, '<script type="text/javascript">' + "\n"
        + '//<!--' + "\n"
        + "const viewer_css = 'viewer.css';" + "\n"
        + "const viewerTouch_css = 'viewer.css';" + "\n"
        + "const ODFViewerPlugin_css = 'viewer.css';" + "\n"
        + "const PDFViewerPlugin_css = 'viewer.css';" + "\n"
        + '//-->' + "\n"
        + '</script>' + "\n"
        + '<script src="viewer.js"'
    ))
    .pipe(gulp.dest('asset/vendor/viewerjs/'))
    .on('end', done);
});

gulp.task('default', gulp.series('clean', 'sync', 'hack_viewerjs'));

gulp.task('install', gulp.task('default'));

gulp.task('update', gulp.task('default'));
