<?php
$manageArrChart1 = getManageCount($allData);
arsort($manageArrChart1);
$manageChart1 = getName($manageArrChart1);
$manageStatsChart1 = getValue($manageArrChart1);
?>
<canvas id="myChart" class="flex-item1">
<script>
  ctx = document.getElementById('myChart');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: [<?php echo $manageChart1 ?>],
      datasets: [{
        label: 'Всего сделок',
        data: [<?php echo $manageStatsChart1 ?>],
      }]
    },
    options: {
}
  });
</script>
</canvas>
