/**
 | Example
 | *watch ko lng un askdesign na json from gulpjson na folder
 | - watch=askdesign gulp watch
 |
 | tapos pwd rin pong iwatch sa loob nun .json un specific na file 
 | para hndi lhat ng .coffee or .less na na under ng .json na file is hndi mawatch
 | - watch=askdesign/askdesign.js  gulp watch
 |
 | - gulp watch
 |
 |
 */


/**
 | Required
 | - npm install
 */
var elixir  = require('laravel-elixir');
var clear   = require('laravel-elixir-clear');
var fs      = require('fs');
var gulp    = require('gulp');
var del     = require('del');
// var livereload = require('gulp-livereload');
//--------------------------------------------------------------------------

/**
 | All Files that need to compile
 | @var array
 */
var jsonFiles = []

/**
 | PRODUCTION
 |
 | Files that need to compile in build
 | @var array
 */
var jsonFilesBuild = []

/**
 | All the Files that need to copy in public folder from vendor Page
 | @var array
 */
var copyFiles = []

/**
 | Path Directory
 | @var array
 */
var path = {
        gulpjson : './gulpjson',
        vendor   : './resources/assets/vendor',
        public   : 'public',
        build    : 'public/build',
    }

/**
 | JSON File Extension
 */
var jsonFormat = '.json'

// ------------------------------------------------------------------------------------------

/**
 | Get the environment (local | production)
 | @var string
 */
var env = fs.readFileSync('./environment', 'utf8');

// ------------------------------------------------------------------------------------------

/**
 | Get all files in GulpJSON Directory
 | @var string
 */
var gulpjson = fs.readdirSync(path.gulpjson);

/**
 | File Specify
 | null = all
 */
var filecssjs = null;

process_env_watch = process.env.WATCH || process.env.watch || process.env.DEV || process.env.dev

if ( process_env_watch ) {

    if ( process_env_watch.indexOf('/') >= 0 ) {
        splitenv = process_env_watch.split('/')
        process_env_watch = splitenv[0]
        filecssjs       = splitenv[1].split(',')
    }

    _gulpjsonfile = process_env_watch.replace(jsonFormat, '')
    gulpjsonfile  = _gulpjsonfile + jsonFormat

    if ( gulpjson.indexOf(gulpjsonfile) < 0 ) {
        console.log("> File Not Found");
        process.exit();
    }
    gulpjson = []
    gulpjson.push(gulpjsonfile)

}

console.log(gulpjson);

/**
 | Segregate all the files base from each json file
 | The filename of file is the base directory of the file
 | except the vendor.json and copy.json
 | @for object
 */
for ( var i = 0; i < gulpjson.length; i++ ) {

    if(gulpjson[i] == '.DS_Store')
        continue;

    var files     = require(path.gulpjson + '/' + gulpjson[i]);
    var main_path = gulpjson[i].substring(0, gulpjson[i].length-5);

    for ( var f = 0; f < files.length; f++ ) {

        var file = files[f]

        if ( main_path != 'copy' ) {

            var isValidFile = true

            if ( filecssjs instanceof Array ) {
                filenameof  = file.filename.split('/').pop()
                isValidFile = filecssjs.indexOf(filenameof) >= 0
            }

            if ( isValidFile === true ) {

                if ( !file.filename ){
                    console.log('> Filename required!');
                    process.exit();
                }
                // var filetype = file.filename.split('.').pop()
                var filetype = file.files[0].split('.').pop()
                file.type    = filetype

                var filename = main_path + '/' + file.filename
                var fileTo   = path.public
                if ( main_path === 'vendor' || main_path === 'basic') {
                    filename = file.filename
                    fileTo   = path.vendor
                }
                if ( !file.path )
                    file.path = fileTo

                file.compileTo = path.public + '/' + filetype + '/' + filename

                if ( filetype === 'less' )
                    file.compileTo = file.compileTo.replace('less/', 'css/')
                else if ( filetype === 'coffee' )
                    file.compileTo = file.compileTo.replace('coffee/', 'js/')

                jsonFiles.push(file)
                jsonFilesBuild.push(file.compileTo)

            }

        }
        else {
            copyFiles.push(file)
        }

    }

}

// // ------------------------------------------------------------------------------------------

// /**
//  | Remove all the sourcemaps
//  | @var boolean
//  */
elixir.config.sourcemaps = false;

// // ------------------------------------------------------------------------------------------

/**
 | Delete directory before compling of files
 | @compile
 */
elixir.extend('remove', function(path) {
    new elixir.Task('remove', function() {
        del(path);
    });
});

/**
 | Set the directory that needs to delete
 | @compile
 */
elixir(function(mix) {
    if(env == 'production')
        mix.remove([ 'public/css', 'public/js', 'public/build' ]);
});

// // ------------------------------------------------------------------------------------------

// *
//  | Compiling process
//  | @compile

elixir(function(mix) {

    for ( var i = 0; i < jsonFiles.length; i++ ) {
        var jsonfile = jsonFiles[i]

        switch (jsonfile.type) {
            case 'less' :
                mix.less( jsonfile.files, jsonfile.compileTo )
                break
            case 'coffee' :
                mix.coffee( jsonfile.files, jsonfile.compileTo )
                break
            case 'css' :
                mix.styles( jsonfile.files, jsonfile.compileTo, jsonfile.path )
                break
            case 'js' :
                mix.scripts( jsonfile.files, jsonfile.compileTo, jsonfile.path )
                break
        }

    }

    if(env == 'production') {
        mix.version(jsonFilesBuild)
        mix.clear([ 'public/css', 'public/js' ])
    }

})

// // ------------------------------------------------------------------------------------------

// /**
//  | Copy Files
//  | @compile
//  */
elixir(function(mix) {
    // var copyToPath = path.public
    // var copyToPath = (env != 'production') ? path.public : path.build

    for ( var i = 0; i < copyFiles.length; i++ ) {
        var copyfile   = copyFiles[i]
        var copyToPath = ( copyfile.is_build == true && env == 'production' ) ? path.build : path.public

        mix.copy( path.vendor + '/' + copyfile.source, copyToPath + '/' + copyfile.destination)
    };
});

// // ------------------------------------------------------------------------------------------