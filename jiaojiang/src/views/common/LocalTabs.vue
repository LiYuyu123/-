<template>
  <div>
    <div class="local">
      <ul class="tabs">
        <li
          v-for="item in source"
          :key="item.value"
          class="tabs-item"
          :class="{
            selected: item.value === tab,
          }"
          @click="select(item)"
        >
          <img
            src="../../assets/image/spot.png"
            alt=""
            v-show="item.value === tab"
          />{{ item.text }}
        </li>
      </ul>
      <div class="content" v-show="tab === '-'"></div>
    </div>
    <LiveData v-show="tab === '-'" />
    <DaLiveData v-show="tab === '+'" />
  </div>
</template>
<script>
import LiveData from "./LiveData";
import DaLiveData from "./DaLiveData";

export default {
  components: { LiveData, DaLiveData },
  data() {
    return {
      tab: "-",
      source: [
        { text: "椒江城区", value: "-" },
        { text: "大陈岛", value: "+" },
      ],
    };
  },
  methods: {
    select() {
      if (this.tab === "-") {
        this.tab = "+";
      } else if (this.tab === "+") {
        this.tab = "-";
      }
    },
  },
};
</script>
<style lang="less" scoped>
.local {
  display: flex;
  justify-content: center;
}
.tabs {
  display: flex;
  font-size: 14px;
  margin-left: 13px;
  &-item {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    font-size: 16px;
    font-family: PingFang SC;
    font-weight: 400;
    color: #ffffff;
    line-height: 15px;
    padding: 27px 0;
    margin-right: 24px;
    > img {
      width: 13px;
      height: 16px;
      margin-right: 2px;
    }
    &.selected::after {
      content: "";
      position: absolute;
      bottom: 20px;
      left: 0;
      width: 100%;
      height: 2px;
      background: #ffffff;
      border-radius: 1px;
    }
  }
}
</style>
