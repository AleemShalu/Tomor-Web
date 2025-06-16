import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel([
      'resources/css/app.css',
      'resources/js/app.js',
    ]),
  ],
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          // Here you can specify manual chunks for better chunking
        },
      },
    },
    chunkSizeWarningLimit: 1000, // Adjust the chunk size warning limit as per your needs
  },
    server: {
        https: true, // Enable HTTPS
    },
});
