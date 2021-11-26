<template>
  <div class="require-wrapper">
    <div class="require-title">活动气象条件要求</div>
    <div class="require-background"></div>
    <div class="weather">
      <div class="weather-one">天气：无雨
        <div class="temperature-within">晴、多云、少云、阴，最好晴天 或者少云</div>
      </div>
      <div class="weather-two">风力：静风或微风
        <div class="temperature-within">静风或微风</div>
      </div>
    </div>
    <div class="temperature">
      <div class="temperature-one">温度：<span class="temperature-within">暂无要求</span></div>
      <div class="temperature-two">能见度：<span class="temperature-within">暂无要求</span></div>
    </div>
    <Warning/>
    <div class="forecast">
      <div class="forecast-title">活动时段气象条件预报</div>
      <img class="forecast-img" src="../assets/img/cry.png" alt=""/>
      <div class="forecast-section">
        <div class="section-word">雨一直下，风一直刮，建议重新选 择日期或取消活动</div>
      </div>
      <div class="forecast-main">
        预计18日18-20时气象条件对此活动有不利影响。
        <div> 预计该时段会有小雨，甚至可能出现大量级降水，建 议对活动时段进行调整或采取其他措施。</div>
      </div>
    </div>
  </div>
</template>
<script>
import Warning from "./Warning";
export default {
  components: {Warning}
}
</script>
<style lang="less" scoped>
.require-wrapper {
  background: linear-gradient(0deg, #FFFFFF 73%, #CAE3FF 100%);
  border-radius: 8px;
  margin: 0 12px;
  padding-bottom: 11px;
  position: relative;
  .require-title {
    padding-top: 13px;
    font-size: 16px;
    font-family: PingFang SC;
    font-weight: 600;
    color: #333333;
    line-height: 15px;
    padding-left: 15px;
  }
  .weather {
    margin-top: 17px;
    font-size: 13px;
    font-family: PingFang SC;
    font-weight: 600;
    color: #333333;
    line-height: 20px;
    display: flex;
    align-items: center;
    padding-left: 15px;
    .weather-one {
      padding-right: 20px;
      .weather-one-within {
        ont-size: 10px;
        font-family: PingFang SC;
        font-weight: 400;
        color: #999999;
        line-height: 12px;
      }
    }
  }

  .temperature {
    font-size: 13px;
    font-family: PingFang SC;
    font-weight: 600;
    color: #333333;
    line-height: 20px;
    display: flex;
    align-items: center;
    margin-top: 15px;
    padding-left: 15px;
    .temperature-one {
      padding-right: 74px;
    }

    .temperature-two {
    }
  }

  .wan {
    height: 59px;
    background: blue;
    margin-top: 20px;
    margin-bottom: 8px;
    margin-right: 12px;
    margin-left: 12px;
  }

  .forecast {
    background: linear-gradient(270deg, #FF5236, #FF8F3C);
    border-radius: 8px;
    margin:0 12px ;
    position: relative;
    .forecast-title {
      font-size: 14px;
      font-family: PingFang SC;
      font-weight: 600;
      color: #FFFFFF;
      line-height: 15px;
      margin:0 12px ;
      padding-top: 10px;
    }

    .forecast-img {
      width: 40px;
      height: 40px;
      position: absolute;
      background: orange;
      border-radius: 50%;
      z-index: 1;
      right: 15px;
      top:15px;
    }

    .forecast-section {
      background-color: rgba(0, 0, 0, 0.15);
      border-radius: 8px;
      height: 60px;
      padding: 8px 9px;
      margin: 8px 11px;
      .section-word {
        width: 240px;
        height: 36px;
        font-size: 16px;
        font-family: PingFang SC;
        font-weight: 600;
        color: #FFFFFF;
        line-height: 20px;
      }
    }

    .forecast-main {
      padding-left: 13px;
      padding-right: 6px;
      padding-bottom: 14px;
      font-size: 13px;
      font-family: PingFang SC;
      font-weight: 300;
      color: #FFFFFF;
      line-height: 20px;
    }
  }
}

.temperature-within {
  font-size: 10px;
  font-family: PingFang SC;
  font-weight: 400;
  color: #999999;
  line-height: 12px;
  width: 140px;
}
.require-background{
  position: absolute;
  width: 66px;
  height: 60px;
  border-radius: 8px;
  background: linear-gradient(180deg, #CAE3FF 0%, #FFFFFF 100%);
  border-bottom-left-radius: 50%;
  right: 0;
  top:0;
}
</style>