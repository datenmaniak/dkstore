// gulpfile.mjs
"use strict";

import { src, dest, watch, series, parallel } from "gulp";

import * as dartSass from "sass";
import gulpSass from "gulp-sass";
const sass = gulpSass(dartSass);

import clearCSS from "gulp-clean-css";
import postcss from "gulp-postcss";
import autoprefixer from "autoprefixer";
import cssnano from "cssnano";
import sourcemaps from "gulp-sourcemaps";
import concat from "gulp-concat";
import terser from "gulp-terser";
import rename from "gulp-rename";
// import imagemin from 'gulp-imagemin';
// import newer from "gulp-newer";
// import webp from 'gulp-webp';
// import imageminWebp from 'imagemin-webp';
import fs from "fs";
import path from "path";
// import imageResize from "gulp-image-resize";
import sharp from "sharp";

import { deleteAsync } from "del";

// evitar que errores detengan el watcher
import plumber from "gulp-plumber";
import { exec } from "child_process";

const paths = {
  scss: "src/scss/**/*.scss",
  js: "src/js/**/*.js",
  images: "src/img/",
};

// ✅ Función reutilizable para verificar y crear carpetas
function ensureFolder(folderPath, label = "carpeta") {
  if (!fs.existsSync(folderPath)) {
    console.warn(`⚠️ La ${label} "${folderPath}" no existe.`);

    try {
      fs.mkdirSync(folderPath, { recursive: true });
      console.log(`📁 Se ha creado automáticamente la ${label}: ${folderPath}`);
    } catch (error) {
      console.error(`❌ Error al crear la ${label}: ${folderPath}`);
      console.error(error.message);
    }
  }
}

// 🧼 Limpia la carpeta build/css
export function cleanCSS() {
  return deleteAsync(["build/css/*"]);
}

export function cleanJS() {
  return deleteAsync(["build/js/**/*.{js,map}"]);
}

function buildStyles() {
  ensureFolder("src/scss", "carpeta SCSS");
  return (
    src(paths.scss)
      .pipe(
        plumber({
          errorHandler: (err) => {
            console.error("[buildStyles] ❌ Error:", err.message);
          },
        })
      )
      .pipe(sourcemaps.init()) // a partir de aqui, compruebo  #1
      .pipe(sass())
      // .on('error', err => { // function compile de Dart Sass
      //   console.error('[buildStyles] ❌ Error al compilar:', err.message);
      // }))
      .pipe(postcss([autoprefixer()])) // #2
      // .pipe(sourcemaps.write('.')) // ⬅️ Escribe el sourcemap junto al CSS
      // .on('error', sass.logError))
      .pipe(dest("build/css"))
      .on("end", () => {
        console.log(
          "\x1b[35m✅ SCSS compilado.\x1b[0m"
        );
      })
  );
}

function buildStylesMini() {
  ensureFolder("src/scss", "carpeta SCSS");
  return (
    src(paths.scss)
      .pipe(
        plumber({
          errorHandler: (err) => {
            console.error("[buildStylesMini] ❌ Error:", err.message);
          },
        })
      )
      .pipe(sourcemaps.init())
      .pipe(sass())
      .pipe(postcss([autoprefixer(), cssnano()]))
      .pipe(rename({ suffix: ".min" }))
      // .pipe(sourcemaps.write('.'))
      .pipe(dest("build/css"))
      .on("end", () => {
        console.log(
          "\x1b[35m✅ SCSS minificado.\x1b[0m"
        );
      })
  );
}

function generateJS() {
  // crear JS normal, legible para usuarios
  ensureFolder("src/js", "carpeta de scripts JS");
  return (
    src(paths.js)
      .pipe(sourcemaps.init())
      // .pipe(terser({ format: { beautify: true } }))  // no indentar
      .pipe(concat("bundle.js")) // nombre legible sin .min
      .on("error", (err) => {
        console.error(
          "[generateJS] ❌ Error al generar los estilos:",
          err.message
        );
      })
      .pipe(dest("build/js"))
      .on("end", () => {
        console.log(
          "\x1b[35m✅ JS script compilado correctamente.\x1b[0m"
        );
      })
  );
}

function generateJSmini() {
  // crear JS minificado
  ensureFolder("src/js", "carpeta de scripts JS");
  return src(paths.js)
    .pipe(sourcemaps.init())
    .pipe(concat("bundle.js"))
    .pipe(terser())
    .on("error", (err) => {
      console.error("[generateJSmini] ❌ Error al minificar JS", err.message);
    })
    .pipe(rename({ suffix: ".min" })) // renombrar antes de escribir sourcemaps
    .pipe(sourcemaps.write("."))
    .pipe(dest("build/js"))
    .on("end", () => {
      console.log(
        "\x1b[35m✅ JS minificado compilado correctamente.\x1b[0m"
      );
    });
}

// se preserva el nombre original del archivo
function resizeImages(done) {
  // console.log('Processing images from ', 'src/img');
  // ensureFolder('src/img', 'carpeta de imágenes');
  const inputDir = "src/img";
  // const inputDir = paths.images;
  const outputDir = "build/img";
  const sizes = [
    { width: 300, suffix: "-sm" },
    { width: 768, suffix: "-md" },
    { width: 1000, suffix: "-lg" },
  ];

  if (!fs.existsSync(inputDir)) {
    console.log(`⚠️ La carpeta "${inputDir}" no existe.`);
    return done();
  }

  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  fs.readdirSync(inputDir).forEach((file) => {
    const ext = path.extname(file).toLowerCase();
    const base = path.basename(file, ext);

    if ([".jpg", ".jpeg", ".png"].includes(ext)) {
      sizes.forEach((size) => {
        sharp(`${inputDir}/${file}`)
          .resize({
            width: size.width, position: 'center'
          })
          // .resize({ width: 535, fit: sharp.fit.cover })
          // .resize({ width: 1000 })
          .toFile(`${outputDir}/${base}${size.suffix}${ext}`)
          // .toFile(`${outputDir}/${base}${ext}`)
          .then(() => {
            console.log(
              /*      ` ✅   ${file} → ${base}${size.suffix}${ext}  `, */
              ` ✅   → ${base}${size.suffix}${ext}`,
              "\x1b[35m correctamente.\x1b[0m"
            );
          })
          .catch((err) => {
            console.error(`❌ Error al redimensionar ${file}:`, err.message);
          });
      });
    }
  });

  done();
}

function convertImagesToWebp(done) {
  const inputDir = "src/img";
  const outputDir = "build/img";

  if (!fs.existsSync(inputDir)) {
    console.warn(`⚠️ La carpeta "${inputDir}" no existe.`);
    return done();
  }

  if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir, { recursive: true });
  }

  fs.readdirSync(inputDir).forEach((file) => {
    const ext = path.extname(file).toLowerCase();
    const base = path.basename(file, ext);

    if ([".jpg", ".jpeg", ".png"].includes(ext)) {
      sharp(`${inputDir}/${file}`)
        .resize({ width: 840 })
        .toFormat("webp")
        .toFile(`${outputDir}/${base}.webp`)
        .then(() => {
          console.log(
            ` ✅  → ${base}.webp `,
            "\x1b[35m generada correctamente.\x1b[0m"
          );
          // console.log(`✅ ${file} → ${base}.webp`);
        })
        .catch((err) => {
          console.error(`❌ Error al convertir ${file} a WebP:`, err.message);
        });
    }
  });

  done();
}

// LEARN
//  generateJS usando Vite
// function generateIndexJS(cb) {
//   const entryPath = "src/js/index.js";

//   if (!fs.existsSync(entryPath)) {
//     console.error(
//       "\x1b[31m[generateJS] ❌ No se encontró index.js en src/js/\x1b[0m"
//     );
//     cb(new Error("Archivo index.js no encontrado"));
//     return;
//   }

//   const content = fs.readFileSync(entryPath, "utf-8").trim();
//   if (content.length === 0) {
//     console.warn(
//       "\x1b[33m[generateJS] ⚠️ index.js está vacío. El bundle no tendrá contenido.\x1b[0m"
//     );
//   }

//   exec("vite build", (err, stdout, stderr) => {
//     if (err) {
//       console.error(
//         "\x1b[31m[generateJS] ❌ Error al compilar con Vite:\x1b[0m",
//         stderr
//       );
//       cb(err);
//       return;
//     }

//     console.log(
//       "\x1b[35m[Violet Pulse] ✅ bundle.js generado en build/js/\x1b[0m"
//     );
//     console.log(stdout);
//     cb();
//   });
// }

function watchFiles() {
  watch(paths.scss, series(buildStyles, buildStylesMini));
  watch(paths.js, series(generateJS, generateJSmini));
  watch(paths.images, series(resizeImages, convertImagesToWebp));
}

console.log("🚀 Iniciando build...");

const buildCSS = series(cleanCSS, buildStyles, buildStylesMini);
const buildImages = series(resizeImages);
const generateWebp = series(convertImagesToWebp);
// const buildImages = parallel(resizeImages, convertImagesToWebp);
export const buildJS = series(cleanJS, generateJS, generateJSmini);

export default parallel(buildCSS, buildJS, buildImages, generateWebp, watchFiles);

// function watchFiles() {
//   watch(paths.scss, series(buildStyles, buildStylesMini));

//   // Reemplazamos la línea que usaba generateJS y generateJSmini
//   watch(paths.js, series(generateIndexJS));

//   watch(paths.images, series(resizeImages, convertImagesToWebp));
// }

// console.log("🚀 Iniciando build...");

// const buildCSS = series(cleanCSS, buildStyles, buildStylesMini);
// const buildImages = parallel(resizeImages, convertImagesToWebp);

// // Reemplazamos buildJS para usar Rollup
// export const buildJS = series(cleanJS, generateIndexJS);

// export default parallel(buildCSS, buildJS, buildImages, watchFiles);

// TODO Evaluar
// LEARN
// function watchFiles() {
//   watch(paths.scss, series(buildStyles, buildStylesMini));
//   watch(paths.js, series(generateIndexJS)); // ahora usa Vite
//   watch(paths.images, series(resizeImages, convertImagesToWebp));
// }

// console.log("🚀 Iniciando build...");

// const buildCSS = series(cleanCSS, buildStyles, buildStylesMini);
// const buildImages = parallel(resizeImages, convertImagesToWebp);
// export const buildJS = series(cleanJS, generateIndexJS);

// export default parallel(buildImages, buildCSS, buildJS, watchFiles);
