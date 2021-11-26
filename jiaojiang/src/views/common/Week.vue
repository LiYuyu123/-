<template>
  <Box :option="option"
    ><div class="content">
      <div class="day">白天</div>
      <div class="late-day">晚上</div>
      <div class="content-wrapper">
        <div class="content-main">
          <div class="content-main-wrapper">
            <div v-for="(i, index) in day" :key="index" class="week-main">
              <div class="week-star">
                {{ setWeek(new Date(addHour(new Date(), i.fh))) }}
              </div>
              <div class="week-date">
                {{ setDate(addDate(i.initdate, i.fh)) }}
              </div>
              <div class="week-weather">{{ setWeather(i.ww) }}</div>
              <div class="day-img">
                <img
                  :src="
                    require('../../assets/image/ww/' + '00' + i.ww + '.png')
                  "
                  alt=""
                />
              </div>
            </div>
          </div>
          <div class="content-main-wrapper">
            <div v-for="(i, index) in lateDay" :key="index" class="week-main">
              <div class="late-day-img">
                <img
                  :src="require('../../assets/image/ww/' + '0' + i.ww + '.png')"
                  alt=""
                />
              </div>
              <div class="late-day-weather">{{ setWeather(i.ww) }}</div>
              <div class="late-day-wind">{{ setWs(i.wscode) }}</div>
              <div class="late-day-level">{{ setWd(i.wdcode) }}</div>
            </div>
          </div>
          <div class="content-main-svg-wrapper">
            <div class="week-svg">
              <div class="top" id="week-top" v-html="topSvg"></div>
              <div class="bottom" id="week-bottom" v-html="bottomSvg"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Box>
</template>
<script>
import Box from "./component/Box";
import {
  CodeToWeather,
  CodeToWs,
  CodeToWd,
  weekDay,
} from "../../assets/js/common";
import svg from "../../assets/js/svg";
import dayjs from "dayjs";
import { getWeek } from "../../http/api";

export default {
  data() {
    return {
      option: { name: "一周天气", word: "" },
      day: [],
      lateDay: [],
      max: [],
      min: [],
      topSvg: "",
      bottomSvg: "",
    };
  },
  components: { Box },
  methods: {
    getStation() {
      return new Promise((resolve, reject) => {
        getWeek({ data: { stid: 58665 } })
          .then((res) => {
            let aWeek = res.data.data.slice(0, 14);
            this.day = aWeek.filter(
              (i) => aWeek.findIndex((d) => d === i) % 2 === 0
            );
            this.lateDay = aWeek.filter(
              (i) => aWeek.findIndex((d) => d === i) % 2 !== 0
            );
            this.max = this.compareMax(this.day, this.lateDay);
            this.min = this.compareMin(this.day, this.lateDay);

            this.topSvg = svg.PrCreateSVG("week-top", this.max, 0, {
              circle: "#FFCF45",
              line: "#FFCF45",
              text: "#fff",
            });
            this.bottomSvg = svg.PrCreateSVG("week-top", this.min, 0, {
              circle: "#A8D4FF",
              line: "#A8D4FF",
              text: "#fff",
            });
            resolve.call(null, res);
          })
          .catch((error) => {
            reject.call(null, error);
          });
      });
    },
    compareMax(oneData, twoData) {
      let max = [];
      for (let i = 0; i < oneData.length && i < twoData.length; i++) {
        oneData[i].tmax > twoData[i].tmax
          ? max.push(oneData[i].tmax)
          : max.push(twoData[i].tmax);
      }
      return max;
    },
    compareMin(oneData, twoData) {
      let min = [];
      for (let i = 0; i < oneData.length && i < twoData.length; i++) {
        oneData[i].tmin < twoData[i].tmin
          ? min.push(
              +oneData[i].tmin === -99.9 ? twoData[i].tmin : oneData[i].tmin
            )
          : min.push(
              +twoData[i].tmin === -99.9 ? oneData[i].tmin : twoData[i].tmin
            );
      }
      return min;
    },
    setDate(date) {
      return dayjs(date).format("M/DD");
    },
    setWeather(data) {
      return CodeToWeather[data];
    },
    setWs(data) {
      return CodeToWs[data];
    },
    setWd(data) {
      return CodeToWd[data];
    },
    setWeek(date) {
      return weekDay[date.getDay()];
    },
    addHour(date, number) {
      return date.setHours(date.getHours() + (number - 12));
    },
    addDate(date, number) {
      return dayjs(date).add(number, "hour");
    },
  },
  created() {},
  mounted() {
    this.getStation();
  },
};
</script>
<style lang="less" scoped>
.content {
  font-family: PingFang SC;
  position: relative;
  .day {
    width: 13px;
    height: 82px;
    background: #f6c944;
    border-radius: 0 2px 2px 0;
    font-size: 12px;
    line-height: 22px;
    color: #0a2042;
    display: flex;
    align-items: center;
    position: absolute;
    top: 90px;
  }
  .late-day {
    width: 13px;
    height: 82px;
    line-height: 22px;
    background: #a8d4ff;
    border-radius: 0 2px 2px 0;
    font-size: 12px;
    color: #0a2042;
    display: flex;
    align-items: center;
    position: absolute;
    bottom: 85px;
  }
  .content-main {
    width: calc(100% + 10);
    overflow-x: scroll;
    margin-right: 10px;
    margin-left: 10px;
    padding: 25px 0;
    position: relative;
    .week-main {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 14.28571%;
      flex-shrink: 0;
      .week-star {
        font-size: 14px;
        font-family: PingFang SC;
        color: #999999;
        padding-bottom: 10px;
      }
      .week-date {
        font-size: 12px;
        font-family: PingFang SC;
        color: #999999;
        padding-bottom: 20px;
      }
      .week-weather {
        font-size: 14px;
        font-family: PingFang;
        font-weight: 500;
        color: white;
        padding-bottom: 10px;
      }
      .day-img {
        width: 20px;
        height: 20px;
        margin-bottom: 137px;
      }
      .late-day-img {
        width: 17px;
        height: 17px;
        margin-bottom: 12px;
      }
      .late-day-weather {
        font-size: 13px;
        font-family: PingFang;
        font-weight: 500;
        color: white;
        padding-bottom: 17px;
      }
      .late-day-wind {
        font-size: 12px;
        font-family: PingFang;
        font-weight: 500;
        color: #aaaaaa;
        padding-bottom: 5px;
      }
      .late-day-level {
        font-size: 12px;
        font-family: PingFang;
        font-weight: 500;
        color: #aaaaaa;
      }
    }
  }
}
.content-wrapper {
}
.top {
  position: absolute;
  width: 100%;
  height: 65px;
  left: 1px;
  top: 120px;
}
.bottom {
  position: absolute;
  width: 100%;
  height: 65px;
  left: 1px;
  top: 180px;
  z-index: 100;
}
.content-main-wrapper {
  display: flex;
  align-items: center;
  width: 140%;
}

.content-main-svg-wrapper {
  width: 140%;
  position: absolute;
  bottom: 370px;
  left: 0;
}
</style>
