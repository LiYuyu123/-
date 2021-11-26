<template>
  <div class="currency">
    <div class="currency-header">
      <div class="name">{{ name }}</div>
      <div class="time">9时14 发布</div>
    </div>
    <slot/>
  </div>
</template>
<script>
export default {
  props: {name}
}
</script>
<style lang="less" scoped>
 .currency{
   padding: 12px;
   background: #FFFFFF;
   box-shadow: 1px 1px 10px 0 rgba(208, 229, 255, 0.3);
   border-radius: 8px;
   .currency-header{
     display: flex;
     align-items: center;
     justify-content: space-between;
     .name{
       font-size: 16px;
       font-family: PingFang SC;
       font-weight: 600;
       color: #333333;
       line-height: 15px;
     }
     .time{
       font-size: 12px;
       font-family: PingFang SC;
       font-weight: 300;
       color: #919191;
       line-height: 15px;
     }
   }
 }
</style>