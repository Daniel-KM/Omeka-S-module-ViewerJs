'use strict';

const gulp = require('gulp');
const del = require('del');
const filter = require('gulp-filter');
const lec = require('gulp-line-ending-corrector');
const replace = require('gulp-string-replace');

const bundle = [
    {
        'source': 'node_modules/node-viewerjs/release/**',
        'dest': 'asset/vendor/viewerjs',
    },
];

gulp.task('clean', function(done) {
    bundle.forEach(function (module) {
        return del.sync(module.dest);
    });
    done();
});

gulp.task('sync', function (done) {
    const f = filter(['**/*.html', '**/*.js', '**/*.css'], {restore: true});
    bundle.forEach(function (module) {
        gulp.src(module.source)
            .pipe(f)
            .pipe(lec())
            .pipe(f.restore)
            .pipe(gulp.dest(module.dest))
            .on('end', done);
    });
});

const hack_viewerjs = function (done) {
    gulp.src(['node_modules/node-viewerjs/release/viewer.js'])
        .pipe(lec())
        .pipe(replace(
            /^        if \( documentUrl \) \{$/gm,
            '        if (! documentUrl ) { documentUrl = parameters.file; }' + "\n"
            + '        if ( documentUrl ) {'
        ))
        .pipe(gulp.dest('asset/vendor/viewerjs/'));

    gulp.src(['node_modules/node-viewerjs/release/index.html'])
        .pipe(lec())
        .pipe(replace(
            /^<script src="viewer.js"/gm,
            '<script type="text/javascript">' + "\n"
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
};

gulp.task('default', gulp.series('clean', 'sync', hack_viewerjs));

gulp.task('install', gulp.task('default'));

gulp.task('update', gulp.series('clean', 'sync', hack_viewerjs));
