import './bootstrap';
import { AntigravityAnimation } from './antigravity';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Check if the specific canvas exists
    if (document.getElementById('antigravity-canvas')) {
        new AntigravityAnimation('antigravity-canvas');
    }
});
