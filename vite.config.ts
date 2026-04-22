import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { resolve } from 'node:path'

const base = '/@dev-server'

// https://vite.dev/config/
export default defineConfig(({ mode }) => ({
	base: mode === 'development' ? base : '/',
	plugins: [
		react(),
		{
			name: 'react-preamble',
			configureServer(server) {
				server.middlewares.use(`${base}/@vite/preamble`, (_, res) => {
					res.setHeader('Content-Type', 'application/javascript')
					res.end(react.preambleCode.replace('__BASE__', `${base}/`))
				})

				server.middlewares.use(`${base}/@vite/client`, async (_, res) => {
					const result = await server.transformRequest('/@vite/client')
					res.setHeader('Content-Type', 'application/javascript')
					res.end(result?.code ?? '')
				})

				// Force Vite to resolve @react-refresh virtual module
				server.middlewares.use(`${base}/@react-refresh`, async (_, res) => {
					const result = await server.transformRequest('/@react-refresh')
					res.setHeader('Content-Type', 'application/javascript')
					res.end(result?.code ?? '')
				})
			}
		}
	],
	publicDir: false,
	build: {
		manifest: 'manifest.json',
		outDir: './public/dist',
		emptyOutDir: true,
		rollupOptions: {
			input: resolve(__dirname, 'src/main.tsx')
		}
	}
}))
