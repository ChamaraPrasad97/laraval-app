import { createApp } from 'vue';
import router from "./router";
import FontAwesomeIcon from './fontawesome.js';
import AppComponent from './src/App.vue';

const app = createApp(AppComponent);

app.use(router).mount("#app");
app.component('font-awesome-icon', FontAwesomeIcon);