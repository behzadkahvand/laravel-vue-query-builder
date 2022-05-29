import {createApp} from 'vue'
import App from './App.vue'
import './index.css'
import store from './store'
import router from './routers'


createApp(App)
    .use(store)
    .use(router)
    .mount('#app')
