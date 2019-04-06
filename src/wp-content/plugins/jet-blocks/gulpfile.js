'use strict';

let gulp         = require('gulp'),
	rename       = require('gulp-rename'),
	notify       = require('gulp-notify'),
	autoprefixer = require('gulp-autoprefixer'),
	sass         = require('gulp-sass'),
	livereload   = require('gulp-livereload'),
	plumber      = require('gulp-plumber' ),
	checktextdomain = require('gulp-checktextdomain');

//frontend
gulp.task( 'jet-blocks', () => {
	return gulp.src( './assets/scss/jet-blocks.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'jet-blocks.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) )
		.pipe( livereload() );
} );

//frontend-template
gulp.task( 'jet-blocks-template', () => {
	return gulp.src( './assets/scss/jet-blocks-template.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'jet-blocks.css' ) )
		.pipe( gulp.dest( './assets/css/templates' ) )
		.pipe( notify( 'Compile Sass Done!' ) )
		.pipe( livereload() );
} );

//editor
gulp.task( 'jet-blocks-editor', () => {
	return gulp.src( './assets/scss/jet-blocks-editor.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'jet-blocks-editor.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) )
		.pipe( livereload() );
} );

//admin
gulp.task( 'jet-blocks-admin', () => {
	return gulp.src( './assets/scss/jet-blocks-admin.scss' )
		.pipe(
			plumber( {
				errorHandler: function( error ) {
					console.log( '=================ERROR=================' );
					console.log( error.message );
					this.emit( 'end' );
				}
			} )
		)
		.pipe( sass( { outputStyle: 'compressed' } ) )
		.pipe( autoprefixer( {
			browsers: ['last 10 versions'],
			cascade:  false
		} ) )

		.pipe( rename( 'jet-blocks-admin.css' ) )
		.pipe( gulp.dest( './assets/css/' ) )
		.pipe( notify( 'Compile Sass Done!' ) )
		.pipe( livereload() );
} );

//watch
gulp.task( 'watch', () => {
	livereload.listen();
	gulp.watch( './assets/scss/**', ['jet-blocks', 'jet-blocks-template', 'jet-blocks-editor', 'jet-blocks-admin'] );
} );

//default
gulp.task( 'default',
	[
		'jet-blocks',
		'jet-blocks-template',
		'jet-blocks-editor',
		'jet-blocks-admin',
		'watch'
	]
);

//check text-domain
gulp.task( 'checktextdomain', () => {
	return gulp.src( '**/*.php' )
		.pipe( checktextdomain( {
			text_domain: 'jet-blocks',
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
			],
		} ) );
} );
