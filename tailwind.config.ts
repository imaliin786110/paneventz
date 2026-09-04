import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: ["class"],
  content: [
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        background: "#0c0c0d",
        foreground: "#d6d6d8",
        gold: {
          300: "#fde68a",
          400: "#fcd34d",
          500: "#f59e0b",
          600: "#c4a472",
          700: "#b45309",
        },
        dark: {
          800: "#18181b",
          900: "#121214",
          950: "#09090b",
        }
      },
      fontFamily: {
        serif: ["var(--font-cormorant)", "Georgia", "serif"],
        sans: ["var(--font-manrope)", "-apple-system", "sans-serif"],
      },
    },
  },
  plugins: [],
};
export default config;