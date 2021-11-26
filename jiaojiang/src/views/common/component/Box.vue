<template>
  <div class="box">
    <div class="box-header">
      <section class="header-word1">
        <span class="br"></span>
        {{ option.name }}
      </section>
      <section class="header-word2">{{ option.word }}</section>
    </div>
    <slot />
  </div>
</template>
<script>
export default {
  props: ["option"],
};
</script>
<style lang="less" scoped>
.box {
  background: rgba(0, 0, 0, 0.7);
  margin-top: 3px;
  .box-header {
    display: flex;
    justify-content: space-between;
    padding-top: 24px;
    .header-word1 {
      font-size: 16px;
      font-family: PingFang;
      font-weight: 800;
      color: white;
      line-height: 20px;
      margin-left: 12px;
      .br {
        display: inline-block;
        width: 3px;
        height: 14px;
        background: white;
        margin-right: 3px;
      }
    }
    .header-word2 {
      font-size: 12px;
      font-family: PingFang SC;
      font-weight: 400;
      color: #999999;
      line-height: 20px;
      opacity: 0.7;
      margin-right: 30px;
    }
  }
}
</style>
