<template>
  <Box :option="option">
    <div class="life-wrapper">
      <section
        class="life"
        v-for="(i, index) in lifeData"
        :key="index"
        @click="click(i.name)"
      >
        <img
          class="life-img"
          :src="require('../../assets/image/' + i.imgName + '.png')"
          alt=""
          v-show="visible !== i.name"
        />
        <div class="life-word1" v-show="visible !== i.name">
          {{ i.name }}
        </div>
        <div class="life-word2" v-show="visible !== i.name">
          {{ i.level }}级
        </div>
        <div class="visible-word" v-show="visible === i.name">
          {{ string(i.content) }}
        </div>
      </section>
    </div>
  </Box>
</template>
<script>
import Box from "./component/Box";
// import axios from "axios";
import { getLife } from "../../http/api";
export default {
  data() {
    return {
      option: { name: "生活指数", word: "查看更多" },
      lifeData: [],
      visible: "",
    };
  },
  components: { Box },
  mounted() {
    this.getLife();
  },
  methods: {
    getLife() {
      return new Promise((resolve, reject) => {
        getLife()
          .then((res) => {
            let resData = res.data.data;
            resData.splice(6, 1);
            this.lifeData = resData;
            resolve.call(null, res);
          })
          .catch((error) => {
            reject.call(null, error);
          });
      });
    },
    string(str) {
      return str.match(/级，(\S*)/)[1];
    },
    click(data) {
      if (this.visible !== data) {
        this.visible = data;
      } else if (this.visible === data) {
        this.visible = "";
      }
    },
  },
};
</script>
<style lang="less" scoped>
.life-wrapper {
  padding-bottom: 30px;
  display: flex;
  flex-wrap: wrap;
  .life {
    text-align: start;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 37px;
    width: 33.33%;
    .life-img {
      width: 28px;
      height: 28px;
      margin-bottom: 11px;
    }
    .life-word1 {
      font-size: 11px;
      font-family: PingFang;
      font-weight: 500;
      color: #999999;
      line-height: 20px;
    }
    .life-word2 {
      font-size: 14px;
      font-family: PingFang;
      font-weight: bold;
      color: white;
      line-height: 20px;
    }
  }
}
.visible-word {
  height: 79px;
  margin: 0 6px;
  font-size: 13px;
  font-family: PingFang;
  font-weight: 500;
  color: #ffffff;
  line-height: 20px;
  margin-left: 20px;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 4;
  overflow: hidden;
}
@media (max-width: 320px) {
  .visible-word {
    padding: 0 5px;
  }
}
</style>
