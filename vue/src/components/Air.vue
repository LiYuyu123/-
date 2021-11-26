<template>
  <div class="content">
    <Currency name="空气质量">
      <div class="air">
        <div class="air-wrapper">
          <img src="../assets/img/tree.png" alt="">
          <div class="air-number">优 <div>93</div></div>
          <div class="air-br"></div>
          <div class="air-word">空气质量令人满意，基本无空气污染， 各类人群可正常活动。</div>
        </div>
      </div>
    </Currency>
    <div class="footer">
      特别说明：天有不测风云，以上预测仅供参考。
    </div>
  </div>
</template>
<script>
import Currency from "./Currency";

export default {
  components: {Currency}
}
</script>
<style lang="less" scoped>
.content {
  margin: 0 12px;
  padding-top: 13px;

  .air {
    background: #F0F5FC;
    border-radius: 8px;
    font-family: PingFang SC;
    margin-top: 12px;
    .air-wrapper {
      padding-top: 13px;
      padding-bottom: 14px;
      padding-left: 13px;
      display: flex;
      align-items: center;
      img {
        width: 24px;
        height: 32px;
      }
      .air-number {
        padding-left: 12px;
        display: flex;
        flex-direction: column;
        font-size: 16px;
        font-weight: 600;
        color: #00BC80;
        line-height: 20px;
      }
      .air-br {
        margin-left: 30px;
        width: 1px;
        height: 45px;
        background: #CCCCCC;
        border-radius: 0px;
      }

      .air-word {
        padding-left: 11px;
        font-size: 12px;
        font-weight: 400;
        color: #333333;
        line-height: 18px;
        padding-right: 22px;
      }
    }
  }
}
.footer {
  text-align: center;
  font-size: 10px;
  font-family: PingFang SC;
  font-weight: 300;
  color: #999999;
  line-height: 18px;
  padding-top: 20px;
  padding-bottom: 63px;
}
</style>