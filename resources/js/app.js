import collapse from '@alpinejs/collapse';
import Alpine from 'alpinejs';
import CruxUI from 'crux-ui';

Alpine.plugin(collapse);
Alpine.plugin(CruxUI);

window.Alpine = Alpine;
Alpine.start();
