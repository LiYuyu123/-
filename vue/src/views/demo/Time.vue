<template>
  <ol>
    <li>00:00</li>
    <li class="two-li">06:00</li>
    <li class="three-li">12:00</li>
    <li class="five-li">18:00</li>
    <li class="end-li">00:00</li>
  </ol>
</template>
<script>
export default {}
</script>
<style lang="less" scoped>
ol {
  font-size: 11px;
  font-family: PingFang SC;
  font-weight: 400;
  color: #FFFFFF;
  line-height: 12px;
  display: flex;
  align-items: center;
  margin-left:40px ;

  li {
    width: 25%;
  }
  .two-li{
    text-align: center;
    padding-right: 20px;
  }
  .three-li{
    text-align: center;
  }
  .five-li{
    text-align: end;
    padding-right: 5px;
  }
  .end-li{
    text-align: end;
  }
}
</style>