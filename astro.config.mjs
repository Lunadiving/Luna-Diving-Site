// @ts-check
import { defineConfig } from 'astro/config';

import tailwindcss from '@tailwindcss/vite';

// https://astro.build/config
export default defineConfig({
  site: 'https://astro.luna-diving-thailand.com',
  vite: {
    plugins: [tailwindcss()]
  }
});