module.exports = {
    content: require('fast-glob').sync([
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ]),
  theme: {
    extend: {},
  },
  plugins: [],
}
