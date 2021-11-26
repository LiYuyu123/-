<template>
  <div class="header-wrapper">
    <div class="background-img">
      <img src="../assets/img/home.jpg" alt="">
    </div>
    <div class="background-header">
    <div class="header-title">2021洞头海岛旅游嘉年华</div>
    <div class="header-time">
      <div class="activity">
        <span class="activity-time">活动时间：</span>
        <span class="activity-date">9月18日 18:00-20:00</span>
      </div>
      <div class="place">
        <span class="place-word">活动地点：</span>
        <span class="place-name">海霞公园</span>
      </div>
    </div>
    <div class="distance">距离开始还有
      <div class="distance-word">03</div>
      天
      <div class="distance-word">05</div>
      小时
      <div class="distance-word">28</div>
      分
    </div>
      <div class="color">
      </div>
    </div>
    </div>
</template>
<script>
export default {}
</script>
<style lang="less" scoped>
.header-wrapper {
  position: relative;
  .background-img {
    z-index: 1;
    width: 100%;
    img {
      width: 100%;
    }
  }
  .background-header {
    position: absolute;
    height: 225px;
    bottom:-120px;
    width: 100%;
    background: linear-gradient(6deg, #1E64B9 57.99999999999999%, rgba(30, 100, 185, 0) 100%);
    .header-title {
      position: relative;
      top:50px;
      font-size: 22px;
      font-family: PingFang SC;
      font-weight: 500;
      color: #FFFFFF;
      text-shadow: 1px 1px 3px rgba(2, 105, 251, 0.6);
      padding-left: 12px;
    }

    .header-time {
      display: flex;
      position: relative;
      top:45px;
      font-size: 11px;
      font-family: PingFang SC;
      font-weight: 300;
      color: #FFFFFF;
      line-height: 30px;
       padding-left: 12px;
      .activity {
        .activity-time {
        }

        .activity-date {
        }
      }

      .place {
        padding-left: 20px;

        .place-word {
        }

        .place-name {

        }
      }
    }
  }

  .distance {
    position: relative;
    top:50px;
    display: flex;
    padding-left: 12px;
    font-size: 12px;
    font-family: PingFang SC;
    font-weight: 600;
    color: #FFFFFF;
    line-height: 18px;

    .distance-word {
      width: 25px;
      height: 20px;
      font-size: 16px;
      text-align: center;
      font-family: PingFang SC;
      font-weight: 600;
      color: #FFFFFF;
      border-radius: 4px;
      background: rgba(255, 255, 255, 0.2);
      margin: 0 2px;
    }
  }
}
.color{
  position: relative;
  bottom: -60px;
  height: 100px;
  background: linear-gradient(0deg, rgba(246, 246, 250, 0.98) 42%, rgba(246, 246, 250, 0) 100%);
}
</style>