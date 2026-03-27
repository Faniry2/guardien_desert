import './bootstrap';

import Alpine from 'alpinejs';
import persist from '@alpinejs/persist'; // ← ajouter

Alpine.plugin(persist);    // ← ajouter
window.Alpine = Alpine;    // déjà présent
Alpine.start(); 
