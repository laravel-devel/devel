/**
 * Libraries
 */
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Vue.js
 */
window.Vue = require('vue');

// Form
Vue.component('v-form', require('./dashboard/form/Form').default);

// Form elements/inputs
Vue.component('v-form-el', require('./dashboard/form/Element').default);

new Vue({
    el: '#vue',
});