<template>
  <div class="data-wrapper">
    <div class="data-date">{{this.dayjs(tideData.oneMax.obstime,'MM月')}}
      <div class="date-day">{{this.dayjs(tideData.oneMax.obstime,'DD')}}</div>
    </div>
    <div class="data-dr"></div>
    <div class="tide">第一高潮
      <div class="tide-time">{{tideData.oneMax.obstime ? this.dayjs(tideData.oneMax.obstime,'hh时mm分') : '-:-'}}</div>
      <div class="tide-m">{{tideData.oneMax.value ? tideData.oneMax.value+'cm' : '-:-'}}</div>
    </div>
    <div class="tide">第二高潮
      <div class="tide-time">{{tideData.max.obstime ? this.dayjs(tideData.max.obstime,'hh时mm分') : '-:-'}}</div>
      <div class="tide-m">{{tideData.max.value ? tideData.max.value+'cm' : '-:-'}}</div>
    </div>
    <div class="tide">第一低潮
      <div class="tide-time">{{tideData.oneMin.obstime ? this.dayjs(tideData.oneMin.obstime,'hh时mm分') : '-:-'}}</div>
      <div class="tide-m">{{tideData.oneMin.value ? tideData.oneMin.value+'cm' : '-:-'}}</div>
    </div>
    <div class="tide">第二低潮
      <div class="tide-time">{{tideData.min.obstime ? this.dayjs(tideData.min.obstime,'hh时mm分') : '-:-'}}</div>
      <div class="tide-m">{{tideData.min.value ? tideData.min.value+'cm' : '-:-'}}</div>
    </div>
  </div>
</template>
<script>
import dayjs from "dayjs";

export default {
  props: ['tideData'],
  methods:{
    dayjs(date,string){
      return dayjs(date).format(string)
    }
  }
}
</script>
<style lang="less" scoped>
.data-wrapper {
  display: flex;
  margin: 4px 4px;
  background: #FFFFFF;
  border-radius: 6px;
  align-items: center;

  .data-date {
    font-weight: 500;
    color: #9F9F9F;
    line-height: 15px;
    font-size: 11px;
    padding-left: 13px;
    white-space: nowrap;

    .date-day {
      padding-top: 7px;
      font-size: 20px;
      font-weight: bold;
      color: #0066CC;
      line-height: 15px;
    }
  }

  .data-dr {
    height: 40px;
    border: 1px solid #E5EEFD;;
    margin: 0 13px;
  }

  .tide {
    margin: 11px 0;
    font-size: 10px;
    font-weight: 400;
    line-height: 25px;
    text-align: center;
    margin-right: 25px;
    white-space: nowrap;
    .tide-time {
      font-size: 12px;
      font-weight: bold;
      color: #000000;
      white-space: nowrap;
    }

    .tide-m {
      font-size: 12px;
      font-weight: bold;
      color: #DB3B4E;
      white-space: nowrap;
    }
  }
}
</style>