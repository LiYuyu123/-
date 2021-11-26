<template>
  <div class="weather">
    <Currency name="天气实况">
      <div class="weather-father">
        <div class="weather-wrapper" v-for="(i,index) in data" :key="index" :class="[i.img]">
          <div class="main">
            <img :src="require('../assets/img/'+i.img+'.png')" alt="">
            <div class="main-word">{{i.name}}</div>
            <div class="main-number">{{i.number}}</div>
          </div>
        </div>
      </div>
    </Currency>
  </div>
</template>
<script>
import Currency from "./Currency";

export default {
  data() {
    return {
      data: [{name: '温度', number: '32.0°C', img: 'af'},
        {name: '近1小时降雨', number: '1.4mm', img: 'ag'},
        {name: '能风度', number: '2.6km', img: 'ae'},
        {name: '东北风', number: '2.5m/s', img: 'ad'},
      ]
    }
  },
  components: {Currency}
}
</script>
<style lang="less" scoped>
.weather-father {
  display: flex;
  align-items: center;
}

.weather {
  margin: 0 12px;
  padding-top: 13px;

  .weather-wrapper {
    width: 25%;
    border-radius: 8px;
    margin-top: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 8px;
    background-repeat: no-repeat;
    background-size: cover;
    .main {
      padding-top: 4px;
      text-align: center;
      padding-bottom: 15px;
      .main-word {
        padding: 4px 0;
        font-size: 12px;
        font-family: PingFang SC;
        font-weight: 400;
        color: #333333;
      }
      .main-number {
        font-size: 16px;
        font-family: PingFang SC;
        font-weight: 600;
        color: #333333;
        line-height: 18px;
      }
      >img{
        width: 48px;
      }
    }
    &.af{
      background-image: url("../assets/img/bg.png");
    }
    &.ag{
      background-image: url("../assets/img/bd.png");
    }
    &.ae{
      background-image: url("../assets/img/bc.png");
    }
    &.ad{
      background-image: url("../assets/img/ba.png");
    }
  }
}
</style>