<template>
  <div class="inversion">
    <div class="inversion-wrapper">
      <div class="word">09月18日09时 - 09月18日10时1H降水预报</div>
      <div class="number">
        <div class="number-br">
          <div class="number-one"></div>
          <div class="number-two"></div>
          <div class="number-three"></div>
          <div class="number-four"></div>
          <div class="number-five"></div>
          <div class="number-six"></div>
          <div class="number-seven"></div>
        </div>
        <section class="number-main">
        <div>50</div>
        <div>40</div>
        <div>7</div>
        <div>15</div>
        <div>1</div>
        </section>
      </div>
    </div>
    <div class="inversion-footer"><div class="footer-word">当前海霞公园周边正在降雨，预计活动时段也有 降雨可能。</div></div>
  </div>
</template>
<script>
export default {}
</script>
<style lang="less" scoped>
.inversion{
  .inversion-wrapper{
    position: relative;
    margin-bottom: 4px;
    height: 180px;
    background: #99DCFF;
    border-radius: 8px;
     .word{
       padding-left: 6px;
       padding-top: 7px;
       font-size: 12px;
       font-family: PingFang SC;
       color: #333333;
     }
    .number{
      position: absolute;
      width: 32px;
      height: 93px;
      background: #ecf8ff;
      border-radius: 4px;
      display: flex;
      align-items: center;
      padding-left: 5px;
      right: 7px;
      top:80px;
      .number-br{
        border-radius: 4px;
        width: 8px;
        height: 84px;
        .number-one{
          width: 8px;
          height: 12px;
          background: #75153F;
          border-radius: 4px 4px 0 0;
        }
        .number-two{
          width: 8px;
          height: 12px;
          background: #E538F2;
        }
        .number-three{
          width: 8px;
          height: 12px;
          background: #0013D8;

        }
        .number-four{
          width: 8px;
          height: 12px;
          background: #77B7F9;

        }
        .number-five{
          width: 8px;
          height: 12px;
          background: #63B64F;

        }
        .number-six{
          width: 8px;
          height: 12px;
          background: #B7EF99;
        }
        .number-seven{
          width: 8px;
          height: 12px;
          background: #FFFFFF;
          border-radius: 0 0 4px 4px;
        }
      }
      .number-main{
        padding-left: 2px;
        font-size: 9px;
        color: #333333;
      }
    }
  }
  .inversion-footer{
    background: rgba(8, 137, 255, 0.1);
    border: 1px solid #0889FF;
    border-radius: 8px;
    .footer-word{
      padding-top: 8px;
      padding-left: 10px;
      padding-bottom: 9px;
      font-size: 14px;
      font-family: PingFang SC;
      font-weight: 400;
      color: #0889FF;
      line-height: 18px;
    }
  }
}
</style>