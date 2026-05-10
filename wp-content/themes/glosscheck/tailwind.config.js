/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './front-page.php',
    './assets/js/*.js',
  ],
  theme: {
    extend: {
      fontFamily: { sans: ['Manrope', 'sans-serif'] },
      colors: {
        accent: '#00d68f',
        'accent-dim': '#00a36e',
        surface: '#0c0c10',
        card: '#131318',
        border: '#1e1e26',
        muted: '#b7c3b3',
      },
    },
  },
  corePlugins: {
    preflight: false,
  },
  safelist: [
    'text-muted/55', 'text-muted/65', 'text-muted/70', 'text-muted/75', 'text-muted/80',
    'text-accent/70', 'text-accent/80',
    'text-amber-200/70', 'text-amber-400/70',
    'bg-accent/10', 'bg-accent/15', 'bg-accent/[0.10]',
    'bg-card/70', 'bg-card/90',
    'bg-muted/10',
    'border-accent/20', 'border-border/50',
    { pattern: /text-muted\/\d+/, variants: ['hover'] },
    { pattern: /sm:max-w-\[75%\]/ },
  ],
};
