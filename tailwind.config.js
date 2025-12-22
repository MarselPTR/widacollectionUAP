/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js}"
  ],
  theme: {
    extend: {
      colors: {
        primary: "#ff6b6b",
        secondary: "#4ecdc4",
        dark: "#292f36",
        light: "#f7fff7",
        accent: "#ffe66d",
      },
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
      }
    },
  },
  plugins: [],
};
