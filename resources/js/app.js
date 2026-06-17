import './cadastro';
import './senha';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';

Alpine.plugin(mask);
Alpine.start();

window.Alpine = Alpine;