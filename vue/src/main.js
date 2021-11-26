import Vue from 'vue'
import App from './App.vue'
import router from './router'
import './assets/less/reset.less'
import 'vant/lib/index.css';
import {Tabs,Tab} from "vant";
import HighchartsVue from 'highcharts-vue';

Vue.use(HighchartsVue )
Vue.use(Tabs)
Vue.use(Tab)
Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
