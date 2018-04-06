'use strict'

/*Gulp*/
var gulp = require('gulp');
var watch = require('gulp-watch');
var plumber = require('gulp-plumber');
var concat = require('gulp-concat');
var concatCss = require('gulp-concat-css');
var uglify = require('gulp-uglify');
const express = require('express');

/*PostCss*/
var postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const precss = require('precss');
const postcssUrl = require('postcss-url');
const path = require('path');


gulp.task('css', function() {
	let plugins = [
		precss(),
		autoprefixer({
			browsers: ['last 3 versions']
		}),
		postcssUrl({
			url: 'inline',
			maxSize: 9 
		}),
		postcssUrl({
			url: 'copy',
			basePath: '.',
			useHash: true,
			assetsPath: path.resolve('assets/image')
		})
	];

	return gulp.src(['library/**/*.css', 'css/**/*.css'])
		.pipe(plumber())
		.pipe(postcss(plugins))
		.pipe(concatCss('style.css'))
		.pipe(postcss([cssnano]))
		.pipe(gulp.dest('assets/'));
});

gulp.task('js', function() {
	return gulp.src('library/**/*.js')
		.pipe(plumber())
		.pipe(concat('app.js'))
		.pipe(gulp.dest('assets/'));
});

gulp.task('server', function() {
	var app = express();

	app.use('/', express.static('.'));

	app.listen(3000, function () {
		console.log('Server listening on port 3000!');
	});
});

gulp.task('watch', function() {
	gulp.watch('library/**/*.css', ['css']);
	gulp.watch('library/**/*.js', ['js']);
});


gulp.task('default', ['css', 'js', 'watch'], function (){
	console.log('Building files');
})