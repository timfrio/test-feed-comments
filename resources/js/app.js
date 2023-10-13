import './bootstrap';
import './include/icons';
import { createApp } from 'vue';
import App from './vue/App.vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Pagination from 'v-pagination-3';

createApp(App)
    .component('font-awesome-icon', FontAwesomeIcon)
    .component('pagination', Pagination)
    .mount('#app')
