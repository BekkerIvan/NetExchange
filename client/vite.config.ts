import tailwindcss from '@tailwindcss/vite';
import devtoolsJson from 'vite-plugin-devtools-json';
import { sveltekit } from '@sveltejs/kit/vite';
import { defineConfig } from 'vite';
import dotenv from 'dotenv';
dotenv.config();
export default defineConfig({
	preview: {
		port: process.env.PORT,
		host: process.env.HOST
	},
	server: {
		port: process.env.PORT,
		host: process.env.HOST
	},
	plugins: [
		tailwindcss(),
		sveltekit(),
		devtoolsJson()
	]
});
