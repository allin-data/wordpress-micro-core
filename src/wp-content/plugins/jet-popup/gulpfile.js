'use strict';

let gulp         = require('gulp'),
	rename       = require('gulp-rename'),
	notify       = require('gulp-notify'),
	autoprefixer = require('gulp-autoprefixer'),
	sass         = require('gulp-sass'),
	minify       = require('gulp-minify'),
	uglify       = require('gulp-uglify'),
	livereload   = require('gulp-livereload'),
	plumber      = require('gulp-plumber' ),
	checktextdomain = require('gulp-checktextdomain');

gulp.task('jet-popup-frontend-css', () => {
	return gulp.src('./assets/scss/jet-popup-frontend.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('jet-popup-frontend.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'))
		.pipe(livereload());
});

gulp.task('jet-popup-admin-css', () => {
	return gulp.src('./assets/scss/jet-popup-admin.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('jet-popup-admin.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'))
		.pipe(livereload());
});

gulp.task('jet-popup-preview-css', () => {
	return gulp.src('./assets/scss/jet-popup-preview.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('jet-popup-preview.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'))
		.pipe(livereload());
});

gulp.task('jet-popup-editor-css', () => {
	return gulp.src('./assets/scss/jet-popup-editor.scss')
		.pipe(
			plumber( {
				errorHandler: function ( error ) {
					console.log('=================ERROR=================');
					console.log(error.message);
					this.emit( 'end' );
				}
			})
		)
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('jet-popup-editor.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'))
		.pipe(livereload());
});

// js
gulp.task( 'jet-popup-frontend-minify', () => {
	return gulp.src( './assets/js/jet-popup-frontend.js' )
		.pipe( uglify() )
		.pipe( rename({ extname: '.min.js' }) )
		.pipe( gulp.dest( './assets/js/') )
		.pipe( notify('js Minify Done!') );
});

gulp.task( 'jet-popup-admin-minify', () => {
	return gulp.src( './assets/js/jet-popup-admin.js' )
		.pipe( uglify() )
		.pipe( rename({ extname: '.min.js' }) )
		.pipe( gulp.dest( './assets/js/') )
		.pipe( notify('js Minify Done!') );
});

gulp.task( 'jet-popup-editor-minify', () => {
	return gulp.src( './assets/js/jet-popup-editor.js' )
		.pipe( uglify() )
		.pipe( rename({ extname: '.min.js' }) )
		.pipe( gulp.dest( './assets/js/') )
		.pipe( notify('js Minify Done!') );
});

//watch
gulp.task( 'watch', () => {
	livereload.listen();
	gulp.watch( './assets/scss/**', ['jet-popup-frontend-css']);
	gulp.watch( './assets/scss/**', ['jet-popup-admin-css']);
	gulp.watch( './assets/scss/**', ['jet-popup-preview-css']);
	gulp.watch( './assets/scss/**', ['jet-popup-editor-css']);
	gulp.watch( './assets/js/*.js', ['jet-popup-frontend-minify'] );
	gulp.watch( './assets/js/*.js', ['jet-popup-admin-minify'] );
	gulp.watch( './assets/js/*.js', ['jet-popup-editor-minify'] );
});

gulp.task( 'checktextdomain', () => {
	return gulp.src( ['**/*.php', '!cherry-framework/**/*.php'] )
		.pipe( checktextdomain( {
			text_domain: 'jet-popup',
			keywords:    [
				'__:1,2d',
				'_e:1,2d',
				'_x:1,2c,3d',
				'esc_html__:1,2d',
				'esc_html_e:1,2d',
				'esc_html_x:1,2c,3d',
				'esc_attr__:1,2d',
				'esc_attr_e:1,2d',
				'esc_attr_x:1,2c,3d',
				'_ex:1,2c,3d',
				'_n:1,2,4d',
				'_nx:1,2,4c,5d',
				'_n_noop:1,2,3d',
				'_nx_noop:1,2,3c,4d',
				'translate_nooped_plural:1,2c,3d'
			]
		} ) );
} );

