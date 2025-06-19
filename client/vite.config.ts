import tailwindcss from '@tailwindcss/vite';
import devtoolsJson from 'vite-plugin-devtools-json';
import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';
import dotenv from 'dotenv';
dotenv.config();

export default defineConfig({
	preview: {
		port: process.env.CLIENT_PORT,
		host: process.env.CLIENT_HOST
	},
	server: {
		port: process.env.CLIENT_PORT,
		host: process.env.CLIENT_HOST
	},
	hmr: {
		host: process.env.CLIENT_HOST,
		protocol: "ws",
	},
	plugins: [
		tailwindcss(),
		sveltekit(),
		devtoolsJson()
	]
});
