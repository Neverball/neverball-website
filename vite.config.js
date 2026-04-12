import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        rollupOptions: {
            input: 'resources/js/addon-tool.js',
            output: {
                entryFileNames: '[name].[hash].js',
                assetFileNames: '[name].[hash][extname]',
            },
        },
        manifest:    true,
        outDir:      'dist',
    },
    define: { global: 'globalThis' },
});
