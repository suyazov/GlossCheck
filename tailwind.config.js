module.exports = {
  content: ['./wp-content/themes/glosscheck/**/*.php', './wp-content/plugins/glosscheck-deepseek-chat/**/*.php'],
  theme: {
    extend: {
      fontFamily: { sans: ['system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'] },
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
};
