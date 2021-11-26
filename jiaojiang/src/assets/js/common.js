const CodeToWeather = {
  0: "晴",
  1: "多云",
  2: "阴",
  3: "阵雨",
  4: "雷阵雨",
  5: "雷阵雨伴冰雹",
  6: "雨夹雪",
  7: "小雨",
  8: "中雨",
  9: "大雨",
  10: "暴雨",
  11: "大暴雨",
  12: "特大暴雨",
  22: "中到大雨",
  13: "阵雪",
  14: "小雪",
  15: "中雪",
  16: "大雪",
  17: "暴雪",
  18: "雾",
  19: "冻雨",
  20: "沙尘暴",
  21: "小到中雨",
  23: "大到暴雨",
  24: "暴雨转大暴雨",
  25: "大暴雨转特大",
  26: "小到中雪",
  27: "中到大雪",
  28: "大到暴雪",
  29: "浮尘",
  30: "扬沙",
  31: "强沙尘暴",
  53: "霾",
};
const weekDay = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];

const CodeToWs = {
  0: "微风",
  1: "3-4级",
  2: "4-5级",
  3: "5-6级",
  4: "6-7级",
  5: "7-8级",
  6: "8-9级",
  7: "9-10级",
  8: "10-11级",
};

const CodeToWd = {
  0: "-",
  1: "东北风",
  2: "东风",
  3: "东南风",
  4: "南风",
  5: "西南风",
  6: "西风",
  7: "西北风",
  8: "北风",
};

const windDirect = (val) => {
  let direction = "东北风";
  if (0 <= val && val <= 22.5) {
    direction = "偏北风";
  }
  if (val > 22.5 && val <= 67.5) {
    direction = "东北风";
  }
  if (val > 67.5 && val <= 112.5) {
    direction = "偏东风";
  }
  if (val > 112.5 && val <= 157.5) {
    direction = "东南风";
  }
  if (val > 157.5 && val <= 202.5) {
    direction = "偏南风";
  }
  if (val > 202.5 && val <= 247.5) {
    direction = "西南风";
  }
  if (val > 247.5 && val <= 292.5) {
    direction = "偏西风";
  }
  if (val > 292.5 && val <= 337.5) {
    direction = "西北风";
  }
  if (val > 337.5 && val <= 360) {
    direction = "偏北风";
  }
  return direction;
};
const fmtWindLevel = (v) => {
  let value = Number(v);
  let windSName = "微风";
  if (value >= 0 && value <= 5.4) windSName = "0-3级";
  if (value > 5.4 && value <= 7.9) windSName = "3-4级";
  if (value > 7.9 && value <= 10.7) windSName = "4-5级";
  if (value > 10.7 && value <= 13.8) windSName = "5-6级";
  if (value > 13.8 && value <= 17.1) windSName = "6-7级";
  if (value > 17.1 && value <= 20.7) windSName = "7-8级";
  if (value > 20.7 && value <= 24.4) windSName = "8-9级";
  if (value > 24.4 && value <= 28.4) windSName = "9-10级";
  if (value > 28.4 && value <= 32.6) windSName = "10-11级";
  if (value > 32.6) {
    windSName = "12级以上";
  }
  return windSName;
};

const windRating = (val) => {
  const windArr = [
    0.2, 1.5, 3.3, 5.4, 7.9, 10.7, 13.8, 17.1, 20.7, 24.4, 28.4, 32.6, 999,
  ];
  for (let i = 0; i < windArr.length; i++) {
    if (val < windArr[i]) return i + "级";
  }
  return windArr.length - 1 + "级";
};

const getAqiLevel = (val) => {
  const value = +val;
  let obj = { cn: "优", level: 1 };
  if (!value) {
    return { cn: "-", level: 1 };
  }
  if (value <= 50) {
    obj.cn = "优";
    obj.level = 1;
  } else if (50 < value && value <= 100) {
    obj.cn = "良";
    obj.level = 2;
  } else if (100 < value && value <= 150) {
    obj.cn = "轻度污染";
    obj.level = 3;
  } else if (150 < value && value <= 200) {
    obj.cn = "中度污染";
    obj.level = 4;
  } else if (200 < value && value <= 300) {
    obj.cn = "重度污染";
    obj.level = 5;
  } else {
    obj.cn = "严重污染";
    obj.level = 6;
  }
  return obj;
};

const menus = [
  { title: "椒江天气", route: { name: "Index" }, img: "index.png" },
  { title: "生活指数", route: { name: "LivingDetail" }, img: "living.png" },
  { title: "短临短期", route: { name: "Short" }, img: "short.png" },
  {
    title: "预警信号",
    route: { name: "FramePage", params: { type: "warn" } },
    img: "warning.png",
  },
  {
    title: "天气快报",
    route: { name: "FramePage", params: { type: "awsreport" } },
    img: "report.png",
  },
  {
    title: "自动站",
    route: { name: "FramePage", params: { type: "aws" } },
    img: "aws.png",
  },
  {
    title: "雷达卫星",
    route: { name: "FramePage", params: { type: "radar" } },
    img: "radar.png",
  },
  {
    title: "台风路径",
    route: { name: "FramePage", params: { type: "typhoon" } },
    img: "typhoon.png",
  },
  { title: "掌上平台", route: { name: "Platform" }, img: "phone.png" },
  {
    title: "柑橘预报",
    route: { name: "Orange" },
    img: "report.png",
  },
];

const warningAreaInfos = {
  杭州: { center: [119.2, 30] },
  宁波: { center: [121.2, 30] },
  温州: { center: [120.2, 27.9] },
  绍兴: { center: [120.5, 29.8] },
  衢州: { center: [118.5, 29] },
  丽水: { center: [119.2, 28.3] },
  台州: {
    center: [120.9, 29],
    children: {
      椒江: { center: [121.43, 28.67] },
      路桥: { center: [121.37, 28.58] },
      三门: { center: [121.37, 29.11] },
      玉环: { center: [121.23, 28.12] },
      黄岩: { center: [121.26, 28.64] },
      天台: { center: [121.03, 29.14] },
      仙居: { center: [120.73, 28.84] },
      温岭: { center: [121.37, 28.36] },
      临海: { center: [121.13, 28.84] },
    },
  },
  舟山: { center: [122, 30.15] },
  湖州: { center: [119.8, 30.9] },
  嘉兴: { center: [120.5, 30.7] },
  金华: { center: [119.8, 29.3] },
};
const warningColors = ["#3366ff", "#ebde06", "#fd9800", "#d82c28"];

export const orangeInfos = {
  freeze: {
    name: "冻害指数",
    type: "day",
    startTime: "12-01",
    endTime: "02-15",
    hourSpan: 24,
    daySpan: 1,
    varID: "TMin",
    varName: "最低气温",
    unit: "℃",
    discribe: "即在0℃以下的低温使作物体内结冰，对作物造成的伤害。",
    crops: [
      {
        name: "温州蜜柑",
        startTime: "12-01",
        endTime: "02-15",
        levels: [-9, -7, -5],
        levelsCn: ["3级", "2级", "1级", "无"],
        colors: [
          "(240, 191, 92)",
          "(255, 235, 102)",
          "(133, 210, 246)",
          "(255, 255, 255)",
        ],
      },
      {
        name: "四季柚",
        startTime: "12-01",
        endTime: "02-15",
        levels: [-4, -2, 0],
        levelsCn: ["3级", "2级", "1级", "无"],
        colors: [
          "(240, 191, 92)",
          "(255, 235, 102)",
          "(133, 210, 246)",
          "(255, 255, 255)",
        ],
      },
      {
        name: "椪柑",
        startTime: "12-01",
        endTime: "02-15",
        levels: [-11, -9, -7, -5],
        levelsCn: ["4级", "3级", "2级", "1级", "无"],
        colors: [
          "(240, 130, 92)",
          "(240, 191, 92)",
          "(255, 235, 102)",
          "(133, 210, 246)",
          "(255, 255, 255)",
        ],
      },
      {
        name: "胡柚",
        startTime: "12-01",
        endTime: "02-15",
        levels: [-11, -9, -7, -5],
        levelsCn: ["4级", "3级", "2级", "1级", "无"],
        colors: [
          "(240, 130, 92)",
          "(240, 191, 92)",
          "(255, 235, 102)",
          "(133, 210, 246)",
          "(255, 255, 255)",
        ],
      },
    ],
  },
  sun: {
    name: "日灼指数",
    type: "day",
    startTime: "05-21",
    endTime: "09-30",
    hourSpan: 24,
    daySpan: 3,
    varID: "TMax",
    varName: "最高气温",
    unit: "℃",
    discribe: "植物受高温伤害的一种现象。",
    crops: [
      {
        name: "四季柚",
        startTime: "05-21",
        endTime: "09-30",
        levels: [35, 37, 39],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
      {
        name: "温州蜜柑",
        startTime: "06-21",
        endTime: "08-31",
        levels: [35, 37, 39],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
      {
        name: "椪柑",
        startTime: "07-21",
        endTime: "08-31",
        levels: [35, 37, 39],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
    ],
  },
  crake: {
    name: "裂果指数",
    type: "day",
    startTime: "04-01",
    endTime: "09-30",
    hourSpan: 24,
    daySpan: 1,
    varID: "Pr",
    varName: "降水",
    unit: "mm",
    discribe: "是指植物成熟后，受降雨影响，果实崩裂或开裂射出的植物现象。",
    crops: [
      {
        name: "四季柚",
        startTime: "04-01",
        endTime: "09-30",
        levels: [50, 100, 150],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
      {
        name: "温州蜜柑",
        startTime: "08-01",
        endTime: "09-20",
        levels: [50, 100, 150],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
      {
        name: "椪柑",
        startTime: "08-01",
        endTime: "09-20",
        levels: [50, 100, 150],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
      {
        name: "胡柚",
        startTime: "04-01",
        endTime: "09-30",
        levels: [28, 45, 60],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
    ],
  },
  lodging: {
    name: "倒伏指数",
    type: "day",
    startTime: "01-01",
    endTime: "12-31",
    hourSpan: 24,
    daySpan: 1,
    varID: "WindEx",
    varName: "极大风",
    unit: "m/s",
    discribe: "直立生长的作物成片发生歪斜，甚至全株匍倒在地的现象。",
    crops: [
      {
        name: "温州蜜柑",
        startTime: "01-01",
        endTime: "12-31",
        levels: [28.4, 32.6, 36.9],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
      {
        name: "椪柑",
        startTime: "01-01",
        endTime: "12-31",
        levels: [28.4, 32.6, 36.9],
        levelsCn: ["无", "1级", "2级", "3级"],
        colors: [
          "(255, 255, 255)",
          "(133, 210, 246)",
          "(255, 235, 102)",
          "(240, 191, 92)",
        ],
      },
    ],
  },
  pick: {
    name: "采摘适宜度",
    type: "week",
    startTime: "09-21",
    endTime: "01-31",
    hourSpan: 24,
    daySpan: 1,
    varID: "Pr",
    varName: "降水",
    unit: "mm",
    discribe: "天气条件是否适宜采摘",
    crops: [
      {
        name: "温州蜜柑",
        startTime: "10-21",
        endTime: "01-31",
        levelsCn: ["适宜", "较适宜", "不适宜"],
        levels: [1.5, 2.5],
        colors: ["(133, 210, 246)", "(255, 235, 102)", "(240, 191, 92)"],
      },
      {
        name: "四季柚",
        startTime: "10-01",
        endTime: "11-09",
        levelsCn: ["适宜", "较适宜", "不适宜"],
        levels: [1.5, 2.5],
        colors: ["(133, 210, 246)", "(255, 235, 102)", "(240, 191, 92)"],
      },
      {
        name: "椪柑",
        startTime: "10-21",
        endTime: "01-31",
        levelsCn: ["适宜", "较适宜", "不适宜"],
        levels: [1.5, 2.5],
        colors: ["(133, 210, 246)", "(255, 235, 102)", "(240, 191, 92)"],
      },
      {
        name: "蜜柑(早熟)",
        startTime: "09-21",
        endTime: "11-10",
        levels: [1.5, 2.5],
        levelsCn: ["适宜", "较适宜", "不适宜"],
        colors: ["(133, 210, 246)", "(255, 235, 102)", "(240, 191, 92)"],
      },
    ],
  },
  pesticide: {
    name: "农药喷洒适宜度",
    type: "day",
    startTime: "01-01",
    endTime: "12-31",
    hourSpan: 6,
    daySpan: 0,
    varID: "Mixed",
    varName: "混合要素",
    unit: "mm",
    discribe: "天气条件是否适宜喷洒农药",
    crops: [
      {
        name: "温州蜜柑",
        startTime: "01-01",
        endTime: "12-31",
        levelsCn: ["适宜", "较适宜", "不适宜"],
        levels: [1.5, 2.5],
        colors: ["(133, 210, 246)", "(255, 235, 102)", "(240, 191, 92)"],
      },
    ],
  },
};

export const stationInfos = {
  58661: "海门街道",
  58665: "洪家街道",
  58666: "大陈镇",
  K8001: "白云街道",
  K8003: "葭沚街道",
  K8103: "前所街道",
  K8104: "章安街道",
  K8107: "下陈街道",
  K8109: "三甲街道",
};

export {
  CodeToWeather,
  weekDay,
  CodeToWs,
  CodeToWd,
  windDirect,
  windRating,
  fmtWindLevel,
  menus,
  getAqiLevel,
  warningAreaInfos,
  warningColors,
};
