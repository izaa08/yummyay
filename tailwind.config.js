/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        'yummy-pink': '#FF69B4',
        'yummy-white': '#FFFFFF',
      },
    },
  },
  plugins: [],
}