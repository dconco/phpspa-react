import { phpspa } from '@dconco/phpspa-vite-plugin'
import { defineConfig } from 'vite'
import { resolve } from 'node:path'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig(({ mode }) => {
	return {
		base: mode === 'development' ? '/@dev-server' : '/',
		plugins: [react(), phpspa()],
		publicDir: false,
		build: {
			manifest: 'manifest.json',
			outDir: './public/dist',
			emptyOutDir: true,
			rollupOptions: {
				input: resolve(__dirname, 'src/main.tsx')
			}
		}
	}
})
