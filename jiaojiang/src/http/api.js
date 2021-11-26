import example from "./index";

//获取生活指数
export function getLife(options) {
  return example({
    method: "post",
    url: "http://www.tz121.com/jiaojiangjc/api/index.php/index/getLiving",
  })(options);
}

// 获取降水量等信息
export function getStation(options) {
  return example({
    method: "post",
    url: "https://api.intweather.com/v3.1/index.php/aws/hour/station",
  })(options);
}

//获取预表信息
export function getShorImpending(options) {
  return example({
    method: "post",
    url: "http://www.tz121.com/jiaojiangjc/api/index.php/index/getFcstData",
  })(options);
}

//获取一周天气信息
export function getWeek(options) {
  return example({
    method: "post",
    url: "http://api.intweather.com/v3.1/index.php/forecast/city/station",
  })(options);
}

//获取降水信息
export function getRain(options) {
  return example({
    method: "post",
    url: "http://lxqx.metgs.com/wcwechat/api/index.php/Home/twoHourPr",
  })(options);
}
