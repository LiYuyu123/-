<template>
  <div class="live-data">
    <div class="live-word">{{ cityData.time }}实况数据</div>
    <div class="temperature-wrapper">
      <div class="temperature-main">
        <div class="temperature-number">{{ cityData.t }}<span>℃</span></div>
        <div class="temperature-word">体感温度 {{ cityData.tg }}℃</div>
      </div>
    </div>
    <div class="warning-wrapper">
      <div class="warning-main">
        <div class="img-wrapper">
          <img src="../../assets/image/aqi.png" alt="" />
        </div>
        <div class="warning-word">- -</div>
      </div>
      <div class="warning-main">
        <img src="../../assets/image/wraning.png" alt="" class="warning-img" />
        <div class="warning-word">当前无预警</div>
      </div>
    </div>
    <div class="precipitation-wrapper">
      <div class="precipitation" v-for="(i, index) in inforData" :key="index">
        <div class="precipitation-word">
          <img :src="require('../../assets/image/' + i.img + '.png')" alt="" />
          <span>{{ i.name }}</span>
        </div>
        <div class="precipitation-number">{{ i.number }}</div>
      </div>
    </div>
  </div>
</template>
<script>
import dayjs from "dayjs";
import { getStation } from "../../http/api";

export default {
  data() {
    return {
      inforData: [
        { name: "降水量", img: "rain", number: "" },
        { name: "相对湿度", img: "rh", number: "" },
        { name: "偏北风", img: "wind", number: "" },
        { name: "能见度", img: "vis", number: "" },
      ],
      cityData: {},
    };
  },
  mounted() {
    this.getStation();
  },
  methods: {
    getStation() {
      return new Promise((resolve, reject) => {
        getStation({ data: { stid: 58666 } })
          .then((res) => {
            this.cityData = {
              time: dayjs(res.data.data.obstime).format("MM月DD日 HH时"),
              t: res.data.data.t,
              tg: parseInt(res.data.data.t) + 1.5,
            };
            this.inforData[0].number = +res.data.data.pr + "mm";
            this.inforData[1].number = res.data.data.rh + "%";
            this.inforData[2].number = +res.data.data.ws + "m/s";
            this.inforData[3].number = res.data.data.vis + "m";
            resolve.call(null, res);
          })
          .catch((error) => {
            reject.call(null, error);
          });
      });
    },
  },
};
</script>
<style lang="less" scoped>
.live-data {
  font-family: PingFang SC;
  display: flex;
  flex-direction: column;
  align-items: center;
  .live-word {
    font-size: 14px;
    color: #ffffff;
    padding-bottom: 32px;
  }
  .temperature-wrapper {
    width: 235px;
    height: 235px;
    border: 8px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    .temperature-main {
      width: 218px;
      height: 218px;
      background: rgba(0, 0, 0, 0.3);
      border-radius: 50%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      .temperature-number {
        font-size: 56px;
        color: #ffffff;
        > span {
          position: relative;
          font-size: 18px;
          top: -35px;
        }
      }
      .temperature-word {
        font-size: 15px;
        color: #ffffff;
      }
    }
  }
  .warning-wrapper {
    display: flex;
    margin-top: 27px;
    margin-bottom: 21px;
    .warning-main {
      height: 40px;
      padding: 0 15px 0 10px;
      border-radius: 20px;
      line-height: 40px;
      background: rgba(0, 0, 0, 0.3);
      margin-right: 10px;
      display: flex;
      align-items: center;
      .img-wrapper {
        background: #00bc80;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 2px;
        > img {
          position: relative;
          width: 13px;
          height: 13px;
        }
      }
      .warning-word {
        font-size: 14px;
        color: #fff;
        padding-top: 1px;
      }
    }
  }
  .precipitation-wrapper {
    display: flex;
    .precipitation {
      font-size: 14px;
      color: #fff;
      margin: 0 14px;
      .precipitation-word {
        white-space: nowrap;
        display: flex;
        align-items: center;
        > img {
          width: 12px;
          height: 12px;
        }
        > span {
          padding-left: 3px;
        }
      }
      .precipitation-number {
        padding-top: 8px;
      }
    }
  }
}
.warning-img {
  width: 13px;
  height: 13px;
  margin-right: 2px;
}
@media (max-width: 320px) {
  .precipitation-wrapper {
    display: flex;
    .precipitation {
      font-size: 12px !important;
      color: #fff;
      margin: 0 !important;
      padding: 0 8px;
      .precipitation-word {
        white-space: nowrap;
        display: flex;
        align-items: center;
        > img {
          width: 12px;
          height: 12px;
        }
        > span {
          padding-left: 3px;
        }
      }
      .precipitation-number {
        padding-top: 8px;
      }
    }
  }
}
@media (max-width: 320px) {
  .temperature-wrapper {
    width: 135px !important;
    height: 135px !important;
    .temperature-main {
      width: 118px !important;
      height: 118px !important;
      .temperature-number {
        font-size: 30px !important;
        position: relative;
        > span {
          position: absolute;
          font-size: 16px !important;
          transform: translateX(20px);
        }
      }
      .temperature-word {
        font-size: 12px !important;
      }
    }
  }
}
@media (max-width: 320px) {
  .temperature-number {
    position: relative;
    > span {
      position: absolute !important;
      top: -2px !important;
      left: 38px;
    }
  }
}
@media (max-height: 667px) {
  .live-word {
    padding-bottom: 10px !important;
  }
  .warning-wrapper {
    margin-top: 10px !important;
    margin-bottom: 10px !important;
  }
}
</style>
