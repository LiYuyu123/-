import dayjs from "dayjs";
export const create = (data) => {
  const date = dayjs(new Date()).format(" HH");
  const compute = (date, number, string) => {
    return (parseInt(date) + number).toString() + string;
  };
  const categories = [
    compute(date, 0, ":00"),
    compute(date, 0, ":30"),
    compute(date, 1, ":00"),
    compute(date, 1, ":30"),
    compute(date, 2, ":00"),
    compute(date, 2, ":30"),
  ];
  return {
    chart: {
      type: "areaspline",
      backgroundColor: "rgba(0, 0, 0, 0)",
      spacingLeft: 50,
      spacingBottom: 5,
    },
    title: {
      text: undefined,
    },
    xAxis: {
      tickLength: 6, //刻度长度不显示
      lineWidth: 1, //初始线为0
      tickPositions: [0, 1, 2, 3, 4, 5, 6],
      title: {},
      labels: {
        style: {
          color: "#FFFFFF",
          fontSize: "11px",
          fontFamily: " PingFang SC",
        },
        formatter: function () {
          return categories[this.value];
        },
      },
      gridLineWidth: 0,
    },
    yAxis: {
      gridLineWidth: 0,
      tickPositions: [0, 1, 2, 3, 4, 5, 6, 7],
      labels: {
        enabled: false,
        style: {
          color: "#999999",
          fontSize: "10px",
          fontFamily: " PingFang",
        },
      },
      title: {
        text: undefined,
      },
    },
    credits: {
      //除去水印
      enabled: false,
    },
    legend: {
      enabled: false,
    },
    tooltip: {},
    navigator: {
      adaptToUpdatedData: false,
    },
    plotOptions: {},
    series: [
      {
        data: data,
        marker: {
          enabled: false,
          radius: 0,
        },
      },
    ],
  };
};
