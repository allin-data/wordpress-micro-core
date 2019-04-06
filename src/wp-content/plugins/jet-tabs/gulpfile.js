'use strict';

let gulp            = require('gulp'),
	rename          = require('gulp-rename'),
	notify          = require('gulp-notify'),
	autoprefixer    = require('gulp-autoprefixer'),
	sass            = require('gulp-sass'),
	uglify          = require('gulp-uglify'),
	plumber         = require('gulp-plumber');

//frontend
gulp.task('jet-tabs-frontend', () => {
	return gulp.src('./assets/scss/jet-tabs-frontend.scss')
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

		.pipe(rename('jet-tabs-frontend.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task('jet-tabs-editor', () => {
	return gulp.src('./assets/scss/jet-tabs-editor.scss')
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

		.pipe(rename('jet-tabs-editor.css'))
		.pipe(gulp.dest('./assets/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task( 'js-editor-minify', () => {
	return gulp.src( './assets/js/jet-tabs-editor.js' )
		.pipe( uglify() )
		.pipe( rename({ extname: '.min.js' }) )
		.pipe( gulp.dest( './assets/js/') )
		.pipe( notify('js Minify Done!') );
});

gulp.task( 'js-frontend-minify', () => {
	return gulp.src( './assets/js/jet-tabs-frontend.js' )
		.pipe( uglify() )
		.pipe( rename({ extname: '.min.js' }) )
		.pipe( gulp.dest( './assets/js/') )
		.pipe( notify('js Minify Done!') );
});

//watch
gulp.task('watch', () => {
	gulp.watch('./assets/scss/**', ['jet-tabs-frontend']);
	gulp.watch('./assets/scss/**', ['jet-tabs-editor']);
	gulp.watch('./assets/js/*.js', ['js-editor-minify'] );
	gulp.watch('./assets/js/*.js', ['js-frontend-minify'] );
});
