let mix = require('laravel-mix');

require('laravel-mix-polyfill');

// keep this comment as a reminder if polyfill (below) doesn't work
// by default mix now ignores node_modules folder. this option overrides that behaviour
// mix.override((config) => {
//     delete config.watchOptions;
// });

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.(glsl|frag|vert)$/,
        exclude: /node_modules/,
        use: [
          'raw-loader',
          // 'glslify-loader',
        ],
      },
    ],
  },
})

mix.options({
  processCssUrls: false,
  autoprefixer: {
    enabled: true,
    options: {
      overrideBrowserslist: ['last 4 versions', '> 1%'],
      cascade: true,
      grid: true,
    }
  }
})
.setPublicPath('/')
.js('assets/src/app.js', 'assets/js/eventsListing.js')
.vue()
// .sass('assets/src/css/main.scss', 'assets/css/main.css')

.polyfill({
    enabled: true,
    useBuiltIns: "usage",
    targets: "defaults"
 })

//  .autoload({
//     jquery: ['$', 'window.jQuery']
//  })
.version()
;
