<template>
  <div class="prediction-content">
    <div class="chart-br"></div>
    <div class="chart-square"></div>
    <div class="chart-row"></div>
    <div class="chart">
      <div id="chart-hight" :style="{height:260+'px'}"></div>
    </div>
    <div class="content-word">未来三天潮汐预报</div>
    <TideData :tide-data="oneDay"/>
    <TideData :tide-data="twoDay"/>
    <TideData :tide-data="threeDay"/>
    <div class="footer">数据来源：瑞安市自然资源和规划局</div>
  </div>
</template>
<script>
import {create} from "../../assets/js/chart";
import qs from 'qs'
import dayjs from 'dayjs'
import TideData from "./TideData"
import Highcharts from 'highcharts/highstock';

const axios = require('axios');

export default {
  components: {TideData},
  data() {
    return {
      option: {},
      chart: {},
      sub: {
        startDate: dayjs(new Date()).format('YYYY-MM-DD 00:00:00'),
        endDate: dayjs(new Date()).add(2, 'day').format('YYYY-MM-DD') + ' 23:00:00',
        todayEnd: dayjs(new Date()).format('YYYY-MM-DD') + ' 23:00:00',
      },
      oneDay: {
        oneMax: {},
        oneMin: {},
        max: {},
        min: {}
      },
      twoDay: {
        oneMax: {},
        oneMin: {},
        max: {},
        min: {}
      },
      threeDay: {
        oneMax: {},
        oneMin: {},
        max: {},
        min: {}
      }
    }
  },
  watch: {
    chart: {
      handler() {
      },
      deep: true,
      immediate: true
    }
  },
  created() {
  },
  mounted() {
    this.getData()
    this.getChartData()
  },
  methods: {
    getChartData() {
      axios({
            headers: {
              post: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            },
            method: 'POST',
            url: 'http://localhost/lzj/index.php/index/getOneDay',
            data: qs.stringify(this.sub),
          }
      ).then(request => {
        this.option = create(request.data, dayjs(new Date()).format('MM月DD日'))
        this.$nextTick(() => {
          const chart = new Highcharts.Chart('chart-hight', this.option)
          chart.redraw()
        })
      }).catch(error => {
        console.log(error)
      })
    },
    getData() {
      const params = qs.stringify(this.sub)
      axios({
            headers: {
              post: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            },
            method: 'POST',
            url: 'http://localhost/lzj/index.php/index/getIndex',
            data: params,
          }
      ).then(request => {
        const res = request.data
        const currentDate = new Date()
        const toData = res.filter(i => this.compare(i.obstime, currentDate, 0))
        const twoData = res.filter(i => this.compare(i.obstime, currentDate, 1))
        const threeData = res.filter(i => this.compare(i.obstime, currentDate, 2))
        this.oneDay = this.size(this.pushData(this.perry(toData),this.perry(twoData)), toData, this.oneDay)
        this.twoDay = this.size(this.pushData(this.perry(twoData),this.perry(threeData)), twoData, this.twoDay)
        this.threeDay = this.size(this.perry(threeData), threeData, this.threeDay)
      }).catch(error => {
        console.log(error)
      })
    },
    compare(oneData, twoData, number) {
      return dayjs(oneData).format('YYYY年MM月DD日') === dayjs(twoData).add(number, 'day').format('YYYY年MM月DD日')
    },
    setMax(data) {
      let max = []
      for (let i = 0; i < data.length; i++) {
        const one = data[i - 1]
        const three = data[i + 1]
        if (i - 1 > 0 && i + 1 < 26) {
          if (data[i] > one && data[i] > three) {
            max.push(data[i])
          }
        }
      }
      return max
    },
    setMin(data) {
      let min = []
      for (let i = 0; i < data.length; i++) {
        const one = data[i - 1]
        const three = data[i + 1]
        if (i - 1 > 0 ) {
          if (data[i] < one && data[i] < three) {
            min.push(data[i])
          }
        }
      }
      return min
    },
    // setMax(data) {
    //   return Math.max.apply(Math, data.map(i => {
    //     return i.value
    //   }))
    // },
    // setMin(data) {
    //   return Math.min.apply(Math, data.map(i => {
    //     return i.value
    //   }))
    // },
    pushData(oldData,newData){
      const dataAdd=oldData
      dataAdd.push(newData[0])
    return dataAdd
    },
    size(data, backData, obj) {
      const max = this.setMax(data)[1]
      const min = this.setMin(data)[1]
      const oneMax = this.setMax(data)[0]
      const oneMin = this.setMin(data)[0]
      obj.max = this.change(backData.filter(i => parseInt(i.value) === max)[0])
      obj.min = this.change(backData.filter(i => parseInt(i.value) === min)[0])
      obj.oneMax = this.change(backData.filter(i => parseInt(i.value) === oneMax)[0])
      obj.oneMin = this.change(backData.filter(i => parseInt(i.value) === oneMin)[0])
      return obj
    },
    change(data) {
      return data ? data : {obstime: '', value: ''}
    },
    perry(data) {
      const hash = []
      for (let i = 0; i < data.length; i++) {
        const d = data[i]
        hash.push(parseInt(d.value))
      }
      return hash
    }
  }
}
</script>
<style lang="less" scoped>
.prediction-content {
  font-family: PingFang SC;
  position: relative;

  .chart-square {
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 4px 0 4px 15px;
    border-color: transparent transparent transparent #FFFFFF;
    top: 127px;
    right: 0;
    z-index: 1;
  }

  .chart-br {
    display: inline-block;
    position: absolute;
    width: calc(100vw * 320 / 375);
    height: 2px;
    background: #FFFFFF;
    top: 130px;
    margin-left: 48px;
    z-index: 1;
  }

  .chart-row {
    position: absolute;
    width: 1px;
    height: 153px;
    border: 1px solid #FFFFFF;
    left: 47px;
    top: 53px;
    z-index: 1;
  }

  .chart {
    .time {
      position: relative;
      bottom: 38px;
    }
  }

  .content-word {
    font-size: 18px;
    font-weight: bold;
    color: #000000;
    line-height: 15px;
    padding: 23px 0;
    margin-left: 16px;
  }

  .footer {
    text-align: center;
    padding-top: 16px;
    font-size: 10px;
    font-family: PingFang SC;
    font-weight: 400;
    color: #979797;
    line-height: 15px;
  }
}

</style>