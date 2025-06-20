import gulp from 'gulp';
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
import * as babelify from 'babelify';
import bro from 'gulp-bro';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import rename from 'gulp-rename';
import cleanCSS from 'gulp-clean-css';
import zip from 'gulp-zip';
import {deleteAsync} from 'del';
import browserSync from 'browser-sync';
import sourcemaps from 'gulp-sourcemaps';
import merge from 'merge-stream';
import postcss from 'gulp-postcss';
import tailwindcss from '@tailwindcss/postcss';
import autoprefixer from "autoprefixer";

const server = browserSync.create();
const sass = gulpSass( dartSass );

const paths = {
    styles: {
        src: 'assets/css/frontend/frontend.css',
        dest: 'assets/dist'
    },
    adminStyles: {
        src: 'assets/css/admin/admin.css',
        dest: 'assets/dist'
    },
    scripts: {
        src: 'assets/js/frontend/index.js',
        modules: 'assets/js/frontend/modules/**/*.js',
        dest: 'assets/dist'
    },
    adminScripts: {
        src: 'assets/js/admin/index.js',
        modules: 'assets/js/admin/modules/**/*.js',
        dest: 'assets/dist'
    },
};

const watchPath = {
    styles: {
        src: 'assets/css/frontend/frontend.css',
        dest: 'assets/dist'
    },
    adminStyles: {
        src: 'assets/css/admin/admin.css',
        dest: 'assets/dist'
    },
    scripts: {
        src: 'assets/js/frontend/**/*.js',
        dest: 'assets/dist'
    },
    adminScripts: {
        src: 'assets/js/admin/**/*.js',
        dest: 'assets/dist'
    },
};

function serve( done ) {
    server.init( {
        proxy: "https://develop.local",
    } );
    done();
}

/* Not all tasks need to use streams, a gulpfile is just another node program
 * and you can use all packages available on npm, but it must return either a
 * Promise, a Stream or take a callback and call it
 */
export const clean = () => deleteAsync( ['assets/dist/*', 'release', '*.zip'] );

/*
 * Define our tasks using plain functions
 */
function styles() {
    return gulp.src(paths.styles.src)
        .pipe(postcss([
            tailwindcss(),
            autoprefixer(),
        ]))
        .pipe(rename({
            basename: 'frontend',
            suffix: '.min',
        }))
        .pipe(gulp.dest('assets/dist'));
}

export function adminStyles() {
    return gulp.src(paths.adminStyles.src)
        .pipe(postcss([
            tailwindcss(),
            autoprefixer(),
        ]))
        .pipe(rename({
            basename: 'admin',
            suffix: '.min',
        }))
        .pipe(gulp.dest('assets/dist'));
}

export function scripts() {
    const processMain = gulp.src( paths.scripts.src, {sourcemaps: true, allowEmpty: true} )
        .pipe( sourcemaps.init() )
        .pipe( bro( {
            transform: [
                [babelify.configure( {presets: ['@babel/preset-env']} ), {global: true}],
            ],
        } ) )
        .on( 'error', console.log )
        .pipe( concat( 'frontend.min.js' ) )
        .pipe( gulp.dest( paths.scripts.dest ) );

    const copyModules = gulp.src( paths.scripts.modules, {base: 'assets/js/frontend'} )
        .pipe( gulp.dest( paths.scripts.dest ) );

    return merge( processMain, copyModules );
}

export function adminScripts() {
    const processMain = gulp.src( paths.adminScripts.src, {sourcemaps: true, allowEmpty: true} )
        .pipe( bro( {
            transform: [
                babelify.configure( {presets: ['@babel/preset-env']} ),
            ],
        } ) )
        .on( 'error', console.log )
        .pipe( uglify() )
        .pipe( concat( 'admin.min.js' ) )
        .pipe( gulp.dest( paths.adminScripts.dest ) );

    const copyAdminModules = gulp.src( paths.adminScripts.modules, {base: 'assets/js/admin'} )
        .pipe( gulp.dest( paths.adminScripts.dest ) );

    return merge( processMain, copyAdminModules );
}

function reload( done ) {
    server.reload();
    done();
}

export function watch() {
    serve( () => {} );
    gulp.watch( watchPath.styles.src, gulp.series( styles, reload ) );
    gulp.watch( watchPath.scripts.src, gulp.series( scripts, reload ) );

    gulp.watch( watchPath.adminStyles.src, gulp.series( adminStyles, reload ) );
    gulp.watch( watchPath.adminScripts.src, gulp.series( adminScripts, reload ) );
    gulp.watch( '**/*.php', gulp.series( styles, adminStyles, reload ) );
}

function release() {
    return gulp.src( [
        '**',
        '!release/**',
        '!assets/js/**',
        '!assets/scss/**',
        '!README.md',
        '!cypress/**',
        '!build/**',
        '!node_modules/**',
        '!visual-diff/**',
        '!vendor/**',
        '!wpcs/**',
        '!*.{lock,json,xml,js,yml}',
    ] )
        .pipe( gulp.dest( 'release/afzaliwp-gs-gf', {mode: '0755'} ) );
}

function releaseZip() {
    return gulp.src( [
        'release/**',
    ] )
        .pipe( zip( 'afzaliwp-gs-gf.zip' ) )
        // eslint-disable-next-line no-undef
        .pipe( gulp.dest( './' ).on( 'end', () => {
            // Move files from release/afzaliwp-gs-gf to release/
            gulp.src( 'release/afzaliwp-gs-gf/**' )
                .pipe( gulp.dest( 'release' ).on( 'end', () => deleteAsync( 'release' ) ) );
        } ) );
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
        adminScripts
    ),
    release,
    releaseZip
);
const buildStyles = gulp.series(
    clean,
    gulp.parallel(
        styles,
        adminStyles
    ),
);

export default build;