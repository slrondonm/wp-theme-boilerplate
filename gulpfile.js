"use strict";
const { src, dest, task, watch, series, parallel } = require("gulp");
const del = require("del");
const sass = require("gulp-sass");
const sourcemaps = require("gulp-sourcemaps");
const babel = require("gulp-babel");
const cleanCSS = require("gulp-clean-css");
const rename = require("gulp-rename");
const merge = require("merge-stream");
//const htmlreplace = require("gulp-html-replace");
const autoprefixer = require("gulp-autoprefixer");
const browserSync = require("browser-sync").create();

const imagemin = require("gulp-imagemin");

task("clean", () => del(["./dist", "./src/css/app.css"]));

task("vendor:js", () =>
    src([
        "./node_modules/bootstrap/dist/js/*",
        "./node_modules/jquery/dist/*",
        "./node_modules/popper.js/dist/umd/popper.*",
    ]).pipe(dest("./src/js/vendor"))
);

task("vendor:fonts", () =>
    src([
        "./node_modules/@fortawesome/fontawesome-free/**/*",
        "!./node_modules/@fortawesome/fontawesome-free/{less,less/*}",
        "!./node_modules/@fortawesome/fontawesome-free/{scss,scss/*}",
        "!./node_modules/@fortawesome/fontawesome-free/.*",
        "!./node_modules/@fortawesome/fontawesome-free/*.{txt,json,md}",
    ]).pipe(dest("./src/fonts/font-awesome"))
);
task("vendor", parallel("vendor:fonts", "vendor:js"));

task("vendor:build", () => {
    const jsStream = src([
        "./src/js/vendor/bootstrap.bundle.min.js",
        "./src/js/vendor/jquery.slim.min.js",
        "./src/js/vendor/popper.min.js",
    ]).pipe(dest("./assets/js/vendor"));
    const fontStream = src(["./src/fonts/font-awesome/**/*.*"]).pipe(
        dest("./dist/assets/fonts/font-awesome")
    );
    return merge(jsStream, fontStream);
});

task("bootstrap:scss", () =>
    src(["./node_modules/bootstrap/scss/**/*"]).pipe(dest("./src/scss/bootstrap"))
);

task(
    "scss",
    series("bootstrap:scss", function compileScss() {
        return src(["./src/scss/*.scss"])
            .pipe(sourcemaps.init())
            .pipe(
                sass
                    .sync({
                        outputStyle: "expanded",
                    })
                    .on("error", sass.logError)
            )
            .pipe(autoprefixer())
            .pipe(sourcemaps.write())
            .pipe(dest("./src/css"));
    })
);

task(
    "css:minify",
    series("scss", function cssMinify() {
        return src("./src/css/app.css")
            .pipe(cleanCSS())
            .pipe(
                rename({
                    suffix: ".min",
                })
            )
            .pipe(dest("./dist/assets/css"))
            .pipe(browserSync.stream());
    })
);

task("js:minify", () =>
    src(["./src/js/app.js"])
        .pipe(babel({ presets: ["minify"] }))
        .pipe(
            rename({
                suffix: ".min",
            })
        )
        .pipe(dest("./dist/assets/js"))
        .pipe(browserSync.stream())
);

// task("replaceHtmlBlock", () =>
//     src(["*.html"])
//         .pipe(
//             htmlreplace({
//                 js: "src/js/app.min.js",
//                 css: "src/css/app.min.css",
//             })
//         )
//         .pipe(dest("dist/assets/"))
// );

task("watch", function browserDev(done) {
    browserSync.init({
        server: {
            baseDir: "./",
        },
    });
    watch(
        ["src/scss/*.scss", "src/scss/**/*.scss", "!src/scss/bootstrap/**"],
        series("css:minify", function cssBrowserReload(done) {
            browserSync.reload();
            done(); //Async callback for completion.
        })
    );npm
    watch(
        "src/js/app.js",
        series("js:minify", function jsBrowserReload(done) {
            browserSync.reload();
            done();
        })
    );
    watch(["*.php"]).on("change", browserSync.reload);
    done();
});

task("image:build", () =>
    src("./src/img/*")
        .pipe(
            imagemin([
                imagemin.mozjpeg({ quality: 75, progressive: true }),
                imagemin.optipng({ optimizationLevel: 5 }),
                imagemin.svgo({
                    plugins: [{ removeViewBox: true }, { cleanupIDs: false }],
                }),
            ])
        )
        .pipe(dest("./dist/assets/img"))
);

task(
    "build",
    series(
        parallel("css:minify", "js:minify", "vendor", "image:build"),
        "vendor:build",
        function copysrc() {
            return src(["*.php", "/**/*.php"], {
                base: "./",
            }).pipe(dest("./dist"));
        }
    )
);

// Default task
task("default", series("clean", "build"));
