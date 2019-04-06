'use strict';

let gulp         = require('gulp'),
	rename       = require("gulp-rename"),
	notify       = require('gulp-notify'),
	autoprefixer = require('gulp-autoprefixer'),
	sass         = require('gulp-sass' ),
	checktextdomain = require('gulp-checktextdomain');

//css
gulp.task('css-admin', () => {
	return gulp.src('./assets/admin/scss/admin.scss')
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('admin.css'))
		.pipe(gulp.dest('./assets/admin/css/'))
		.pipe(notify('Compile Sass Done!'));
});

gulp.task('css-public', () => {
	return gulp.src('./assets/public/scss/public.scss')
		.pipe(sass( { outputStyle: 'compressed' } ))
		.pipe(autoprefixer({
				browsers: ['last 10 versions'],
				cascade: false
		}))

		.pipe(rename('public.css'))
		.pipe(gulp.dest('./assets/public/css/'))
		.pipe(notify('Compile Sass Done!'));
});

//watch
gulp.task('watch', () => {
	gulp.watch('./assets/admin/scss/**', ['css-admin']);
	gulp.watch('./assets/public/scss/**', ['css-public']);
});

gulp.task( 'checktextdomain', () => {
	return gulp.src( ['**/*.php', '!cherry-framework/**/*.php'] )
		.pipe( checktextdomain( {
			text_domain: 'jet-menu',
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
