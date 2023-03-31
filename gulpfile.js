import gulp from 'gulp';
import dartSass from 'sass';
import gulpSass from 'gulp-sass';
import babelify from 'babelify';
import bro from 'gulp-bro';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import rename from 'gulp-rename';
import cleanCSS from 'gulp-clean-css';
import zip from 'gulp-zip';
import {deleteAsync} from 'del';
import browserify from 'browserify';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';
import cssModulesify from 'css-modulesify';

const sass = gulpSass(dartSass);

const paths = {
    styles: {
        src: 'assets/scss/frontend/frontend.scss',
        dest: 'assets/dist'
    },
    adminStyles: {
        src: 'assets/scss/admin/admin.scss',
        dest: 'assets/dist'
    },
    scripts: {
        src: 'assets/js/frontend/*.js',
        dest: 'assets/dist'
    },
    adminScripts: {
        src: 'assets/js/admin/*.js',
        dest: 'assets/dist'
    },
    controlPanelScripts: {
        src: 'includes/control-panel',
        dest: 'includes/control-panel/dist'
    },
};

const watchPath = {
    styles: {
        src: 'assets/scss/frontend/*.scss',
        dest: 'assets/dist'
    },
    adminStyles: {
        src: 'assets/scss/admin/*.scss',
        dest: 'assets/dist'
    },
    scripts: {
        src: 'assets/js/frontend/*.js',
        dest: 'assets/dist'
    },
    adminScripts: {
        src: 'assets/js/admin/*.js',
        dest: 'assets/dist'
    },
    controlPanelScripts: {
        src: 'includes/control-panel',
        dest: 'includes/control-panel/dist'
    },
}

/* Not all tasks need to use streams, a gulpfile is just another node program
 * and you can use all packages available on npm, but it must return either a
 * Promise, a Stream or take a callback and call it
 */
export const clean = () => deleteAsync(['assets/dist/*', 'release', '*.zip']);

/*
 * Define our tasks using plain functions
 */
export function styles() {
    return gulp.src(paths.styles.src)
        .pipe(sass())
        .pipe(cleanCSS())
        .pipe(rename({
            basename: 'frontend',
            suffix: '.min'
        }))
        .pipe(gulp.dest(paths.styles.dest));
}

export function adminStyles() {
    return gulp.src(paths.adminStyles.src)
        .pipe(sass())
        .pipe(cleanCSS())
        .pipe(rename({
            basename: 'admin',
            suffix: '.min'
        }))
        .pipe(gulp.dest(paths.adminStyles.dest));
}

export function scripts() {
    return gulp.src(paths.scripts.src, {sourcemaps: true})
        .pipe(bro({
            transform: [
                babelify.configure({presets: ['@babel/preset-env']}),
            ],
        }))
        .on('error', console.log)
        .pipe(uglify())
        .pipe(concat('frontend.min.js'))
        .pipe(gulp.dest(paths.scripts.dest));
}

export function adminScripts() {
    return gulp.src(paths.adminScripts.src, {sourcemaps: true})
        .pipe(bro({
            transform: [
                babelify.configure({presets: ['@babel/preset-env']}),
            ],
        }))
        .on('error', console.log)
        .pipe(uglify())
        .pipe(concat('admin.min.js'))
        .pipe(gulp.dest(paths.adminScripts.dest));
}

// This task is for react parts of the plugin. you can copy and paste this task and edit it for other react directories.
// You can disable uglify to increase the speed of the task (for development purposes).
export function controlPanelScripts() {
    return browserify({
        entries: './includes/control-panel/src/index.jsx',
        extensions: ['.jsx'],
        debug: true
    }).plugin(cssModulesify, {
        rootDir: './includes/control-panel',
        output: './includes/control-panel/dist/control-panel.css'
    })

        .transform(babelify.configure({
            presets: ['@babel/preset-env', '@babel/preset-react']
        }))
        .bundle()
        .pipe(source('control-panel.js'))
        .pipe(buffer())
        .pipe(uglify())
        .pipe(gulp.dest('./includes/control-panel/dist'));
}

export function watch() {
    gulp.watch(watchPath.scripts.src, scripts);
    gulp.watch(watchPath.styles.src, styles);

    gulp.watch(watchPath.adminScripts.src, adminScripts);
    gulp.watch(watchPath.adminStyles.src, adminStyles);

    gulp.watch(watchPath.controlPanelScripts.src, controlPanelScripts);
}

function release() {
    return gulp.src([
        '**',
        '!release/**',
        '!assets/js/**',
        '!assets/scss/**',
        '!includes/control-panel/src/**', // the control panel jsx files
        '!README.md',
        '!cypress/**',
        '!build/**',
        '!node_modules/**',
        '!visual-diff/**',
        '!vendor/**',
        '!wpcs/**',
        '!*.{lock,json,xml,js,yml}',
    ])
        .pipe(gulp.dest('release/afzaliwp-boilerplate', {mode: '0755'}));
}

function releaseZip() {
    return gulp.src([
        'release/**',
    ])
        .pipe(zip('afzaliwp-boilerplate.zip'))
        // eslint-disable-next-line no-undef
        .pipe(gulp.dest('./').on('end', () => {
            // Move files from release/afzaliwp-boilerplate to release/
            gulp.src('release/afzaliwp-boilerplate/**')
                .pipe(gulp.dest('release').on('end', () => deleteAsync('release')));
        }));
}

/*
 * Specify if tasks run in series or parallel using `gulp.series` and `gulp.parallel`
 */
const build = gulp.series(
    clean,
    gulp.parallel(
        styles,
        scripts,
        adminStyles,
        adminScripts,
        controlPanelScripts
    ),
    release,
    releaseZip
);

export default build;
