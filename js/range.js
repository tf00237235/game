function rang_role(role, str, agi, int, con, vit) {
    //定義變數
    var chartRadarDOM;
    var chartRadarData;
    var chartRadarOptions;

    //載入雷達圖
    Chart.defaults.global.legend.display = true;
    Chart.defaults.global.defaultFontColor = 'rgba(0,0,74, 1)';
    chartRadarDOM = document.getElementById("chartRadar");
    chartRadarData;
    chartRadarOptions = {
        scale: {
            ticks: {
                fontSize: 12,
                beginAtZero: true,
                maxTicksLimit: 7,
                min: 0,
                max: 50
            },
            pointLabels: {
                fontSize: 15,
                color: '#0044BB'
            },
            gridLines: {
                color: '#009FCC'
            }
        }
    };

    //console.log("---------Rader Data--------");
    var graphData = new Array();
    graphData.push(str);
    graphData.push(agi);
    graphData.push(int);
    graphData.push(con);
    graphData.push(vit);


    //console.log("--------Rader Create-------------");
    //console.log(graphData);

    //CreateData
    chartRadarData = {
        labels: ['力量(' + str + ')', '敏捷(' + agi + ')', '智慧(' + int + ')', '體力(' + con + ')', '體質(' + vit + ')'],
        datasets: [{
            label: role + "先天上限",
            backgroundColor: "rgba(17, 34, 51,0.8)",
            borderColor: "rgba(63,63,74,.8)",
            pointBackgroundColor: "rgba(63,63,74,1)",
            pointBorderColor: "rgba(0,0,0,0)",
            pointHoverBackgroundColor: "#fff",
            pointHoverBorderColor: "rgba(0,0,0,0.3)",
            pointBorderWidth: 5,
            data: graphData
        }]
    };

    //Draw
    var chartRadar = new Chart(chartRadarDOM, {
        type: 'radar',
        data: chartRadarData,
        options: chartRadarOptions
    });
}