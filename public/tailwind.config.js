/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "../app/views/**/*.{php,js}",
    "../app/views/*.{php,js}"
  ],
  theme: {
    extend: {
      screens: {
        xsm: '425px'
      }
    },
  },
  plugins: [],
}