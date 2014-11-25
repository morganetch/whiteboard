var gulp = require('gulp'),
		browserify = require('browserify'),
		buffer = require('gulp-buffer'),
		compass = require('gulp-compass'),
		gutil = require('gulp-util'),
		jshint = require('gulp-jshint'),
		source = require('vinyl-source-stream'),
		sourcemaps = require('gulp-sourcemaps'),
		stylish = require('jshint-stylish'),
		uglify = require('gulp-uglify'),
		minifyCSS = require('gulp-minify-css');


gulp.task('default', ['scripts', 'styles'], function(){
	gulp.watch(['js/src/**/**/*.js'], ['scripts']);
	gulp.watch(['_scss/**/*.scss'], ['styles']);
});

gulp.task('styles', function(){
	return gulp.src('./_scss/*.scss')
		.pipe(compass({
			config_file: './config.rb',
			css: './css',
			sass: '_scss',
			environment: 'production'
		}))
		.on('error', function(err){
			gutil.log(err.message);
			gutil.beep();
			this.emit('end');
		})
		.pipe(minifyCSS())
		.pipe(gulp.dest('./css'));
});

gulp.task('lint', function(){
	return gulp.src('js/src/**/*.js')
		.pipe(jshint())
		.pipe(jshint.reporter(stylish));
});

gulp.task('scripts', ['lint'], function(){
	var bundler = browserify({
		entries: ['./js/src/script.js'],
		debug: true
	});

	return bundler.bundle()
		.on('error', function(err) {
			gutil.log(err.message);
			gutil.beep();
			this.emit('end');
		})
		.pipe(source('script.dist.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({loadMaps: true}))
    	.pipe(uglify())
    	.pipe(sourcemaps.write('../maps'))
		.pipe(gulp.dest('js'));
});
