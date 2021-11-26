<template>
  <div class="future">
    <section class="future-word">{{ tiele }}</section>
    <div class="rain">
      <div class="rain-word">大雨</div>
      <div class="rain-word">中雨</div>
      <div class="rain-word">小雨</div>
    </div>
    <div class="column"></div>
    <div class="row"></div>
    <div id="future-chart" style="height: 150px"></div>
    <footer class="future-footer">
      分钟降雨预报由中国气象局<img
        src="../../assets/image/china-logo.png"
        alt=""
      />
      和彩云科技<img src="../../assets/image/keji.png" alt="" />
      联合出品
    </footer>
  </div>
</template>
<script>
import { create } from "../../assets/js/chart";
import Highcharts from "highcharts/highstock";
import { getRain } from "../../http/api";
export default {
  data() {
    return {
      chartData: [0, 0, 0, 0, 0, 0],
      option: {},
      tiele: "",
    };
  },
  mounted() {
    this.option = create(this.chartData);
    this.$nextTick(() => {
      const chart = new Highcharts.Chart("future-chart", this.option);
      chart.redraw();
    });
    this.getRain();
  },
  methods: {
    getRain() {
      return new Promise((resolve, reject) => {
        getRain()
          .then((res) => {
            this.tiele = res.data.data.prHourDesc;
            resolve.call(null, res);
          })
          .catch((error) => reject.call(null, error));
      });
      // axios
      //   .get("http://lxqx.metgs.com/wcwechat/api/index.php/Home/twoHourPr")
      //   .then((res) => {
      //     this.tiele = res.data.data.prHourDesc;
      //   })
      //   .catch((error) => console.log(error));
    },
  },
};
</script>
<style lang="less" scoped>
.future {
  background: rgba(0, 0, 0, 0.7);
  border-radius: 16px 16px 0 0;
  position: relative;
  .rain {
    position: absolute;
    top: 62px;
    left: 12px;
    .rain-word {
      font-size: 11px;
      font-family: PingFang SC;
      font-weight: 400;
      color: #ffffff;
      line-height: 34px;
    }
  }
  .column {
    position: absolute;
    height: 1px;
    background: #e5e5e5;
    top: 59px;
    left: 48px;
  }
  .row {
    position: absolute;
    width: 1px;
    height: 99px;
    border: 1px dashed #e5e5e5;
    top: 60px;
    left: 48px;
  }
  .future-word {
    font-size: 14px;
    font-family: PingFang;
    font-weight: bold;
    color: #ffffff;
    line-height: 15px;
    padding-top: 20px;
  }
  .future-footer {
    font-size: 10px;
    font-family: PingFang;
    font-weight: 400;
    color: #ffffff;
    line-height: 20px;
    opacity: 0.7;
    padding-bottom: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    > img {
      width: 10px;
      height: 10px;
      margin: 0 4px;
    }
  }
}
@media (width: 320px) {
  .column {
    width: 81% !important;
  }
}
@media (max-width: 375px) {
  .column {
    width: 83%;
  }
}
@media (max-width: 540px) {
  .column {
    width: 84%;
  }
}
@media (max-width: 414px) {
  .column {
    width: 85%;
  }
}
@media (min-width: 768px) {
  .column {
    display: none;
  }
}
@media (max-width: 320px) {
  .future {
    margin-top: 20px !important;
  }
}
@media (max-height: 667px) {
  .future {
    margin-top: 20px !important;
  }
}
@media (height: 653px) {
  .future {
    margin-top: 100px !important;
  }
}
</style>
