<template>
  <div class="home">
    <Header class="header"/>
    <div class="home-main">
      <Requirements/>
      <Weather/>
      <Map/>
       <Air/>
    </div>
  </div>
</template>
<script>
import Header from "../../components/Header";
import Requirements from "../../components/Requirements";
import Weather from "../../components/Weather";
import Map from "../../components/Map";
import Air from "../../components/Air";

export default {
  components: {Air, Map, Weather, Requirements, Header}
}
</script>
<style lang="less" scoped>
.home {
  width: 100%;
  height: 100vh;
  .home-main {
    position: relative;
    top: 30px;
  }

}
</style>