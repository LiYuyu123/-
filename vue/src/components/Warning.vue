<template>
  <div class="warning">
    <div class="warning-img">
      <img src="../assets/img/rain.png" alt="">
    </div>
    <div class="warning-word">
      <div class="word-one">暴雨蓝色预警</div>
      <div class="word-two">2021年07月31日 08时发布</div>
    </div>
    <img src="../assets/img/details.png" alt="">
  </div>
</template>
<script>
export  default {

}
</script>
<style lang="less" scoped>
.warning{
  background: linear-gradient(163deg, #5EB0FF 0%, #398DFF 100%);
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-radius: 8px;
  margin: 0 12px;
  margin-bottom: 8px;
  margin-top: 20px;
  .warning-img{
    margin-left: 10px;
    width: 37px;
    height: 37px;
    background: linear-gradient(163deg, #00A2FF 0%, #008AFF 100%);
    box-shadow: 0 2px 3px 0 rgba(0,143,255,0.36);
    font-family: PingFangSC-Regular, PingFang SC;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    >img{
      width: 20px;
      height: 20px;
      margin: 0;
    }
  }
  .warning-word{
    padding: 7.5px 0;
    .word-one{
      letter-spacing: 8px;
      font-size: 20px;
      font-weight: 600;
      color: #FFFFFF;
      line-height: 28px;
      text-shadow: 0 1px 2px #036BF8;

    }
    .word-two{
      text-align: center;
      font-size: 9px;
      font-weight: 400;
      color: #FFFFFF;
      letter-spacing: 0px;
    }
  }
  img{
    width: 54px;
    height: 21px;
    margin-right: 10px;
  }
}
</style>