/**
 * Libraries
 */
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Vue.js
 */
window.Vue = require('vue');
window.Events = new Vue();
require('./dashboard/functions');

// Form
Vue.component('v-form', require('./dashboard/form/Form').default);
Vue.component('v-form-el', require('./dashboard/form/Element').default);

// Components
Vue.component('v-datatable', require('./dashboard/components/DataTable').default);
Vue.component('v-alert', require('./dashboard/components/Alert').default);
Vue.component('v-notification', require('./dashboard/components/Notification').default);

new Vue({
    el: '#vue',
});