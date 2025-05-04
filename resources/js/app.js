import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCloudArrowDown } from '@fortawesome/free-solid-svg-icons';

library.add(faCloudArrowDown);

app.component('font-awesome-icon', FontAwesomeIcon);

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

app.mount('#app');
