<?php
$allProfitData = getProfitPerClient($allData);
arsort($allProfitData);
$contractor = getName($allProfitData);
$count = count(explode(',', $contractor));
$contractorProfit = getValue($allProfitData);
$total = number_format(array_sum(explode(',', $contractorProfit)), 2, ',', ' ');
$contractor1 = explode(',', $contractor);

?>
<div class="chart4">
    <div class="subbox">
        <canvas id="myChart4"></canvas>
    </div>
</div>

<script>
    Chart.defaults.font.size = 14;
    const labels = [<?php echo $contractor ?>];
    const data1 = [<?php echo $contractorProfit ?>];

    const data = {
        labels: labels,
        datasets: [{
            label: 'Прибыль в разрезе контрагентов',
            data: data1,
            borderWidth: 1,
            backgroundColor: [
                'rgba(80, 191, 78, 0.2)',
            ],
        }],

    };
    const config = {
        type: 'bar',
        data,
        options: {
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                y: {
                    min: 0,
                },

            },
            plugins: {
                datalabels: {
                    align: 'right',
                    anchor: 'center',
                    formatter: (value, dnct1) => {
                        let sum = 0;
                        let dataArr = dnct1.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += Number(data);
                        });
                        let percentage = String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' (' + (value * 100 / sum).toFixed(2) + '%)';
                        return percentage;
                    },
                },
                legend: {
                    display: true,
                    labels: {
                        font: {
                            size: 13
                        }
                    }
                }
            }
        },

        plugins: [ChartDataLabels],
    };

    const myChart4 = new Chart(
        document.getElementById('myChart4'),
        config
    );
    const subbox = document.querySelector('.subbox');
    if (labels.length < 4) {
        subbox.style.height = '300px';
    } else if (labels.length < 6) {
        subbox.style.height = '600px';
    } else if (labels.length < 20) {
        subbox.style.height = '800px';
    } else if (labels.length < 30) {
        subbox.style.height = '1000px';
    } else {
        subbox.style.height = '2400px';
    }
</script>