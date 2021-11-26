import dayjs from "dayjs";

export const create = (data, date) => {
    const level = []
    for (let i = 0; i < data.length; i++) {
        const d = data[i]
        level.push(parseInt(d.value))
    }
    const setTime = (data, value) => {
        return data.filter(i => parseInt(i.value) === parseInt(value))[0]
    }

    const categories = ['00:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', '00:00']
    return {
        chart: {
            type: 'areaspline',
            backgroundColor: '#0889FF',
        },
        title: {
            text: `飞云江流域潮汐(${date})`,
            useHTML: true,
            style: {
                fontSize: '22px',
                fontWeight: 'bold',
                fontFamily: ' PingFang SC',
                color: '#F6F7F9',
            },
            x: -40,
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            min: 0,
            tickLength: 0, //刻度长度不显示
            lineWidth: 0, //初始线为0
            tickPositions: [0, 5, 11, 17, 23],
            title: {
                text: '潮位：cm',
                style: {
                    fontSize: '10px',
                    fontWeight: 400,
                    fontFamily: ' PingFang SC',
                    color: '#FFFFFF',
                },
            },
            labels: {
                style: {
                    color: '#FFFFFF',
                    fontSize: '11px',
                    fontFamily: ' PingFang SC',
                },
                formatter: function () {
                    return categories[this.value]
                }
            },
            gridLineWidth: 1,
            gridLineColor: '#36AAE6',
        },
        yAxis: {
            gridLineColor: '#36AAE6',
            tickPositions: [-300, -200, -100, 0, 100, 200, 300], //指定y轴刻度线值
            labels: {
                style: {
                    color: '#FFFFFF',
                    fontSize: '11px',
                    fontFamily: ' PingFang SC',
                }
            },
            title: {
                text: undefined,
            },
        },
        credits: { //除去水印
            enabled: false,
        },
        legend: {
            enabled: false,
        },
        tooltip: {

            formatter: function () {
                return `<div>时间：${dayjs(setTime(data, this.y).obstime).format('hh:mm')}</div>&nbsp;&nbsp;<div>潮位：${setTime(data,this.y).value}</div>`
            },
        },
        navigator: {
            adaptToUpdatedData: false,
        },
        plotOptions: {},
        series: [{
            lineColor: '#8ac6fc', //曲线颜色
            lineWidth: 1,
            fillColor: 'rgba(54, 170, 230, 0.5)', //背景颜色
            data: level,
            marker: {
                enabled: false,
                radius: 0,
            },
        }]
    }
}