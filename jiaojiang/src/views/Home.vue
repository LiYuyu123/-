<template>
  <div class="home">
    <LocalTabs id="my-tabs" />
    <Future
      id="future"
      ref="future"
      :style="{ marginTop: screenHeight - (420 + 211 + 70) + 'px' }"
    />
    <div>
      <Prediction />
      <Week />
      <Life />
      <Query />
      <Footer />
    </div>
  </div>
</template>

<script>
import LocalTabs from "./common/LocalTabs";
import Prediction from "./common/Prediction";
import Query from "./common/Query";
import Footer from "./common/Footer";
import Future from "./common/Future";
import Life from "./common/Life";
import Week from "./common/Week";

export default {
  name: "Home",
  components: { Future, Footer, Query, Prediction, LocalTabs, Life, Week },
  data() {
    return {
      // h: document.documentElement.clientHeight,
      screenHeight: document.documentElement.clientHeight,
    };
  },
  mounted() {
    window.onresize = () => {
      return (() => {
        window.screenHeight = document.documentElement.clientHeight;
        this.screenHeight = window.screenHeight;
      })();
    };
  },
  methods: {},
};
</script>
<style lang="less" scoped>
.home {
  background-image: url("../assets/image/bg.jpg");
  background-size: cover;
  background-repeat: no-repeat;
  background-attachment: fixed;
  position: relative;
}
.future {
}
</style>
