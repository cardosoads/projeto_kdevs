import { defineConfig } from 'astro/config';

// Site estático. CSS e JS saem como arquivos externos para que a CSP de vercel.json
// possa proibir código inline (script-src 'self'; style-src 'self').
export default defineConfig({
  site: 'https://kdevs.tech',
  output: 'static',
  trailingSlash: 'never',
  build: { inlineStylesheets: 'never', format: 'file' },
  vite: { build: { assetsInlineLimit: 0 } },
});
