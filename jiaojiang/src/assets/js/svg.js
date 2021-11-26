let PrCreateSVG = function (
  obj,
  arr,
  high,
  color,
  fontsize = 12,
  offsetX = 15
) {
  let svgWidth = document.getElementById(obj).offsetWidth;
  let lineWidth = svgWidth / 7; //共有多少个点
  let tmpWidth = 0; //用来调整svg点左右对齐（微调）
  let svgHeight = document.getElementById(obj).offsetHeight; //svg总高度
  let T_top = 5; //距离顶部高度
  let T_height = 15; //svg绘图部分高度
  let T_arr = arr; //svg绘图使用的数组
  let T_min = Math.min.apply(null, T_arr);
  let T_length =
    Math.max.apply(null, T_arr) - T_min == 0
      ? 1
      : Math.max.apply(null, T_arr) - T_min;
  let svgHtml = "";
  svgHtml +=
    '<svg width="' +
    svgWidth +
    '" height="' +
    svgHeight +
    '" version="1.1" xmlns="http://www.w3.org/2000/svg" style="font-size: 12px; " >';
  let pathStr = "<desc>Created with SVG</desc>";
  let circleStr = "";

  let circleArr = [];
  //画svg点和线
  for (let i = 0; i < T_arr.length; i++) {
    circleArr.push({
      x: lineWidth / 2 + i * lineWidth - tmpWidth * i,
      y:
        T_top +
        svgHeight -
        (((T_arr[i] - T_min) / T_length) * T_height +
          (svgHeight - T_height) / 2),
    });
  }
  circleArr.forEach((v, i) => {
    circleStr +=
      '<circle cx="' +
      v.x +
      '" cy="' +
      v.y +
      '" r="4" fill="' +
      color["circle"] +
      '"/>';
    circleStr +=
      '<text x="' +
      (v.x - offsetX) +
      '" y="' +
      (v.y + (high > 0 ? 20 : -15)) +
      '" font-family="Microsoft YaHei" font-size="' +
      fontsize +
      '" style="fill: ' +
      color["text"] +
      ";color:" +
      color["text"] +
      '">' +
      T_arr[i] +
      "℃</text>";
    if (i < circleArr.length - 1)
      pathStr += drawPath(circleArr, i, color["line"]);
  });

  svgHtml += pathStr;
  svgHtml += circleStr;
  svgHtml += "</svg>";
  return svgHtml;
};
let drawPath = function (circleArr, i, color) {
  let tmpPath = "";
  let pt = getCtrlPoint(circleArr, i);
  tmpPath +=
    '<path d="M' +
    circleArr[i].x +
    "," +
    circleArr[i].y +
    " C" +
    pt.pA.x +
    "," +
    pt.pA.y +
    " " +
    pt.pB.x +
    "," +
    pt.pB.y +
    " " +
    circleArr[i + 1].x +
    "," +
    circleArr[i + 1].y +
    ' " stroke="' +
    color +
    '" stroke-width="2" fill="transparent"/>';
  return tmpPath;
};
let getCtrlPoint = function (ps, i, a, b) {
  if (!a || !b) {
    a = 0.25;
    b = 0.25;
  }
  let pAx = 0,
    pAy = 0,
    pBx = 0,
    pBy = 0;
  //处理两种极端情形
  if (i < 1) {
    pAx = ps[0].x + (ps[1].x - ps[0].x) * a;
    pAy = ps[0].y + (ps[1].y - ps[0].y) * a;
  } else {
    pAx = ps[i].x + (ps[i + 1].x - ps[i - 1].x) * a;
    pAy = ps[i].y + (ps[i + 1].y - ps[i - 1].y) * a;
  }
  if (i > ps.length - 3) {
    let last = ps.length - 1;
    pBx = ps[last].x - (ps[last].x - ps[last - 1].x) * b;
    pBy = ps[last].y - (ps[last].y - ps[last - 1].y) * b;
  } else {
    pBx = ps[i + 1].x - (ps[i + 2].x - ps[i].x) * b;
    pBy = ps[i + 1].y - (ps[i + 2].y - ps[i].y) * b;
  }
  return {
    pA: { x: pAx, y: pAy },
    pB: { x: pBx, y: pBy },
  };
};
export default {
  PrCreateSVG,
};
