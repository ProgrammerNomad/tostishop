/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php", 
    "./assets/js/*.js",
    "./woocommerce/**/*.php"
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
      },
      colors: {
        // TostiShop Brand Colors
        primary: '#14175b',    // Deep Navy Blue
        accent: '#e42029',     // Bright Red  
        light: '#ecebee',      // Light Silver White
        
        // Extended color palettes based on brand colors
        navy: {
          50: '#f1f2f9',
          100: '#e3e5f2',
          200: '#c7cbe6',
          300: '#a1a9d5',
          400: '#7983c1',
          500: '#5c65b0',
          600: '#4a519e',
          700: '#3f4581',
          800: '#373c6a',
          900: '#14175b',   // Brand primary
          950: '#0d0f38',
        },
        red: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#ef4444',
          600: '#e42029',   // Brand accent
          700: '#dc2626',
          800: '#b91c1c',
          900: '#991b1b',
          950: '#7f1d1d',
        },
        silver: {
          50: '#ecebee',    // Brand light
          100: '#f8f9fa',
          200: '#e9ecef',
          300: '#dee2e6',
          400: '#ced4da',
          500: '#adb5bd',
          600: '#6c757d',
          700: '#495057',
          800: '#343a40',
          900: '#212529',
        },
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      },
      aspectRatio: {
        'square': '1 / 1',
        'product': '4 / 5',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'bounce-gentle': 'bounceGentle 2s infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        bounceGentle: {
          '0%, 20%, 50%, 80%, 100%': { transform: 'translateY(0)' },
          '40%': { transform: 'translateY(-10px)' },
          '60%': { transform: 'translateY(-5px)' },
        },
      },
      backdropBlur: {
        xs: '2px',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
  corePlugins: {
    aspectRatio: false,
  },
}
