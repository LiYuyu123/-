<template>
  <div class="query">
    <div class="query-word">中国天气网查全国天气</div>
    <img src="../../assets/image/query.png" alt="" />
  </div>
</template>
<script>
export default {};
</script>
<style lang="less" scoped>
.query {
  background: #000000;
  opacity: 0.7;
  margin-top: 3px;
  display: flex;
  align-content: center;
  justify-content: space-between;
  .query-word {
    font-size: 14px;
    font-family: PingFang SC;
    font-weight: 400;
    color: #ffffff;
    line-height: 20px;
    padding-top: 20px;
    padding-bottom: 20px;
    text-align: start;
    padding-left: 22px;
    opacity: 0.7;
  }
  > img {
    width: 17px;
    height: 17px;
    margin-right: 28px;
    margin-top: 20px;
    margin-bottom: 20px;
  }
}
</style>
