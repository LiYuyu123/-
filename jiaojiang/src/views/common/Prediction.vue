<template>
  <div class="prediction">
    <ul class="tabs">
      <li
        v-for="item in source"
        :key="item.value"
        class="tabs-item1"
        :class="{
          selected: item.value === tab,
        }"
        @click="select(item)"
      >
        {{ item.text }}
      </li>
    </ul>
    <div
      class="content"
      v-show="tab === '-'"
      v-html="shorImpending.content"
    ></div>
    <div class="content" v-show="tab === '+'" v-html="shortTerm.content"></div>
  </div>
</template>
<script>
import { getShorImpending } from "../../http/api";
export default {
  data() {
    return {
      tab: "-",
      source: [
        { text: "短临预报", value: "-" },
        { text: "短期预报", value: "+" },
      ],
      shorImpending: {},
      shortTerm: {},
    };
  },
  mounted() {
    this.getShorImpending();
    this.getShortTerm();
  },
  methods: {
    getShorImpending() {
      return new Promise((resolve, reject) => {
        getShorImpending({ data: { cid: 179 } })
          .then((res) => {
            this.shortTerm = res.data.data;
            resolve.call(null, res);
          })
          .catch((error) => {
            reject.call(null, error);
          });
      });
    },
    getShortTerm() {
      return new Promise((resolve, reject) => {
        getShorImpending({ data: { cid: 178 } })
          .then((res) => {
            this.shorImpending = res.data.data;
            console.
            resolve.call(null, res);
          })
          .catch((error) => {
            reject.call(null, error);
          });
      });
    },
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
<style lang="less">
.tabs {
  display: flex;
  font-size: 14px;
  margin-left: 13px;
  &-item1 {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    padding: 27px 0;
    margin-right: 24px;
    &.selected::after {
      content: "";
      position: absolute;
      top: 43px;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(-90deg, #0086ff 0%, #00a9ff 100%);
      box-shadow: 0 4px 8px 0 rgba(0, 136, 255, 0.7);
    }
  }
}
.prediction {
  background: rgba(0, 0, 0, 0.7);
  font-size: 14px;
  font-family: PingFang SC;
  font-weight: 400;
  color: #ffffff;
  margin-top: 3px;
  .content {
    padding-left: 12px;
    padding-right: 21px;
    text-align: start;
    padding-bottom: 28px;
  }
}
.content > p {
  font-size: 14px;
  margin: 3px 0;
}
</style>
