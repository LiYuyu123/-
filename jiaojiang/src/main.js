import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "./assets/less/reset.less";
import "vant/lib/index.css";
import { Tabs, Tab, Icon, Row, Col } from "vant";
import Highcharts from "highcharts";

Vue.use(Highcharts);
Vue.use(Tabs);
Vue.use(Tab);
Vue.use(Icon);
Vue.use(Row);
Vue.use(Col);
Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
