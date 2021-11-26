<template>
  <div class="map">
    <Currency name="降水雷达反演图">
      <van-tabs>
        <van-tab title="10时">
          <Inversion/>
        </van-tab>
        <van-tab title="11时"></van-tab>
        <van-tab title="12时"></van-tab>
        <van-tab title="13时"></van-tab>
        <van-tab title="14时"></van-tab>
      </van-tabs>
    </Currency>
  </div>
</template>
<script>
import Currency from "./Currency";
import Inversion from "./map/Inversion";
export default {
  data() {
    return {}
  },
  components: {Inversion, Currency}
}
</script>
<style lang="less" scoped>
.map {
  margin: 0 12px;
  padding-top: 13px;
}

/deep/ .van-tabs__nav {
  margin-top: 8px;

  .van-tab {
    height: 32px;
    border-radius: 16px;
    background-color: rgba(8, 137, 255, 0.1);
    color: #333;
    margin-right: 10px;

    .van-tab__text {
      font-size: 12px;
      font-family: PingFang SC;
    }
  }
}

/deep/ .van-tabs__line {
  display: none;
}

/deep/ .van-tab--active {
  background: #0889FF !important;
  color: white !important;
}
</style>