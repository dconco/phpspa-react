import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { resolve } from 'node:path'

// https://vite.dev/config/
export default defineConfig({
  base:  '/',
  plugins: [react()],
  publicDir: false,
  build: {
    manifest: 'manifest.json',
    outDir: './public/dist',
    emptyOutDir: true,
    rollupOptions: {
      input: resolve(__dirname, 'src/main.tsx'),
    },
  },
})
