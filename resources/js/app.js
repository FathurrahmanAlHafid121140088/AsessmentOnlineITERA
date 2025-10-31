/* File ini berfungsi sebagai titik masuk (entry point) untuk JavaScript di Laravel + Vite.

   NOTE: File-file JavaScript lainnya masih ada di public/js/ dan di-load langsung di blade.
   Untuk sekarang, kita fokus ke CSS optimization dulu.
   JavaScript optimization bisa dilakukan nanti jika diperlukan.
*/

// Import file bootstrap default dari Laravel
import "./bootstrap";

// Jika ada JavaScript global yang perlu di-bundle, tambahkan di sini
// Contoh:
// import './global.js';

console.log('Vite + Laravel ready!');
