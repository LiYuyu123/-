import axios from "axios";
import qs from "qs";

const sev = axios.create({
  headers: {
    post: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
  },
  //在向服务器发送前，修改请求数据
  transformRequest: function (params) {
    return qs.stringify(params);
  },
  timeout: 15000, //超时时间
});

export default function example({ method, url }) {
  return function http({ data, params } = {}) {
    return sev({
      method,
      url,
      data,
      params,
    });
  };
}
