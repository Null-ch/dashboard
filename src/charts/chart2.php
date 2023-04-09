<?php
$manageArrChart2 = getNomenclature($allData);
arsort($manageArrChart2);
$manageChart2 = getName($manageArrChart2);
$manageStatsChart2  = getValue($manageArrChart2);
?>

<canvas id="myChart1" class="flex-item2">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
  ctx = document.getElementById('myChart1').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [<?php echo $manageChart2 ?>],
      datasets: [{
        label: 'Прибыль',
        data: [<?php echo $manageStatsChart2 ?>],
        borderWidth: 1
      }]
    },
    options: {
        plugins: {

      datalabels: {
        formatter: (value, dnct1) => {
          let sum = 0;
          let dataArr = dnct1.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += Number(data);
          });
          let percentage = (value * 100 / sum).toFixed(2) + '%';
          return percentage;
        },
        color: 'rgba(81, 82, 53)',
      }
    }
  },
  plugins: [ChartDataLabels],
  });
</script>
</canvas>
