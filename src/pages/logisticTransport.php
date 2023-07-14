<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
if (empty($_SESSION['user_id'])) {
	header('Location: /login');
}

$profit = ['Затраты на шины' => 51, 'Затраты на запчасти' => 55, 'Общие затраты' => 72];
$plan = ['Затраты на шины' => 15, 'Затраты на запчасти' => 131, 'Общие затраты' => 33];

$labels = getName($plan);

$profit = getSortBy($plan, $profit);
$profitVal = getValue($profit);

$planProfit = getSortBy($plan, $plan);
$planProfitVal = getValue($planProfit);

$plan = [
	'01.01' => 22,
	'02.01' => 33,
	'03.01' => 41,
	'04.01' => 81,
	'05.01' => 55,
	'06.01' => 614,
	'07.01' => 82,
	'08.01' => 15,
	'09.01' => 33,
	'10.01' => 64,
];
$plan2 = [
	'01.01' => 51,
	'02.01' => 61,
	'03.01' => 22,
	'04.01' => 51,
	'05.01' => 66,
	'06.01' => 415,
	'07.01' => 513,
	'08.01' => 32,
	'09.01' => 66,
	'10.01' => 55,
];

$labels1 = getName($plan);
$Val = getValue($plan);
$Val2 = getValue($plan2);


function getData1($date1 = '', $date2 = '', $inn = '', $method = 'logisticsReport')
{
	if (!$date2) {
		$date2 = date("Y-m-d");
	}
	if (!$date1) {
		$date1 = date("Y-m-d", strtotime($date2 . '- 7 days'));
	}
	if (!$inn) {
		$inn = "5024163243";
	}

	$headers = array('Content-Type:application/json', 'Authorization: Basic ' . base64_encode("СервисGoogleDashboard://GDS%321"));
	$date = [
		'dateFrom' => "$date1",
		'dateTo' => "$date2",
		"organizationITN" => "$inn",
		"tractorCode" => "00503",
		"trailerCode" => "00509",
	];
	$dataString = json_encode($date);

	$url = '185.60.133.5:47800/UATProf/hs/googleDashboard/' . $method;
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = json_decode(curl_exec($ch), true);
	// $result = (array) $result;
	// $result = $result[$dataType];
	return $result;
}

$date1 = '2023-04-01';
$date2 = '2023-06-10';

$allData1 = getData1($date1, $date2);

function trueData($arr, $dataType)
{
	$data = [];
	foreach ($arr[$dataType] as $item) {
		if ($item['КолРейсов'] > 1) {
			$data[] = $item;
		}
	}
	return $data;
}
$testTruck = trueData($allData1, 'dataTracktor');
$testTrailer = trueData($allData1, 'dataTrailer');
function allSpent($dataTruck, $dataTraler)
{
	$gsmTruck = 0;
	$wheelsTruck = 0;
	$repairTruck = 0;

	$wheelsTrailer = 0;
	$repairTrailer = 0;
	foreach ($dataTruck as $value) {
		$repairTruck += (float)$value['Запчасти'];
		$gsmTruck += (float)$value['ГСМ'];
		$wheelsTruck += (float)$value['Шины'];
	}
	foreach ($dataTraler as $value) {
		$repairTrailer += (float)$value['Запчасти'];
		$wheelsTrailer += (float)$value['Шины'];
	}
	return ['Шины (тягачи)' => $wheelsTruck, 'ГСМ (тягачи)' => $gsmTruck, 'Запчасти (тягачи)' => $repairTruck, 'Шины (прицепы)' => $wheelsTrailer, 'Запчасти (прицепы)' => $repairTrailer];
}
function traceWithCargo($data)
{
	$gsm = [];
	$item = '';
	foreach ($data as $value) {
		$item = $value['ТС'];
		$gsm[$item] = 0;
		$gsm[$item] = (float)$value['ПробегСГр'];
	}
	return $gsm;
}
function getGsm($data)
{
	$gsm = [];
	$item = '';
	foreach ($data as $value) {
		$item = $value['ТС'];
		$gsm[$item] = 0;
		$gsm[$item] = (float)$value['ГСМ'];
	}
	return $gsm;
}

function getRepair($data)
{
	$repair = [];
	$item = '';
	foreach ($data as $value) {
		$item = $value['ТС'];
		$repair[$item] = 0;
		$repair[$item] = (float)$value['Запчасти'];
	}
	return $repair;
}

function getWheels($data)
{
	$wheels = [];
	$item = '';
	foreach ($data as $value) {
		$item = $value['ТС'];
		$wheels[$item] = 0;
		$wheels[$item] = (float)$value['Шины'];
	}
	return $wheels;
}

function getCarNumber($data)
{
	$numbers = [];
	foreach ($data as $value) {
		if ($value['ТС']) {
			$numbers[$value['ТС']] = $value['ТС'];
		}
	}
	return $numbers;
}

function allSpentPerCar($numbers, $gsm, $traceWithCargo, $repair, $wheels)
{
	$totalResult = [];
	foreach ($numbers as $key => $value) {
		$numbers[$value] = 0;
		if ($traceWithCargo[$value]) {
			$numbers[$value] += $traceWithCargo[$value];
		}
		if ($gsm[$value]) {
			$numbers[$value] += $gsm[$value];
		}
		if ($repair[$value]) {
			$numbers[$value] += $repair[$value];
		}
		if ($wheels[$value]) {
			$numbers[$value] += $wheels[$value];
		}
	}
	return $numbers;
}

function normalize($arr)
{
	$result = [];
	foreach ($arr as $key => $value) {
		$result[] = ['ТС' => $key, 'Значение' => $value];
	}
	return $result;
}

$traceWithCargo = traceWithCargo($testTruck);
$gsm = getGsm($testTruck);
$repair = getRepair($testTruck);
$wheels = getWheels($testTruck); 
$numbers = getCarNumber($testTruck);
$allSpentPerCar = allSpentPerCar($numbers, $gsm, $traceWithCargo, $repair, $wheels);

asort($gsm);
asort($repair);
asort($wheels);
arsort($traceWithCargo);
arsort($allSpentPerCar);

$dataArray['Пробег с грузом'] = normalize($traceWithCargo);
$dataArray['ГСМ'] = normalize($gsm);
$dataArray['Запчасти'] = normalize($repair);
$dataArray['Шины'] = normalize($wheels);
$dataArray['Расходы'] = normalize($allSpentPerCar); 

$result = allSpent($testTruck, $testTrailer);
$allSpent = array_sum($result);


$res = normalize($traceWithCargo);
?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; ?>
<style>
	/* Стили таблицы (IKSWEB) */
	table.iksweb {
		text-decoration: none;
		border-collapse: collapse;
		width: 100%;
		text-align: center;
		padding: 5px;
		height: 100%;
	}

	table.iksweb th {
		font-weight: normal;
		font-size: 14px;
		color: #ffffff;
		background-color: #38376ec3;
		text-align: center;
	}

	table.iksweb td {
		font-size: 13px;
		color: #354251;
		text-align: center;
		width: 300px;


	}

	table.iksweb td,
	table.iksweb th {
		padding: 10px;
		line-height: 13px;
		/* border: 1px solid #354251; */
		text-align: center;

	}

	/* table.iksweb tr:hover {
		background-color: #f9fafb
	} */

	/* table.iksweb tr:hover td {
		color: #354251;
		cursor: default;
	} */
</style>

<body>
	<div class="block">
		<div class="mainFilter">
			<form style="margin-left: 80px" method="POST">
				<p style="margin: 10px">Выберите дату начала периода: <input type="date" class='center' name="dateFrom" value="<?php echo $dateFrom ?>" max="<?php echo $dateTo ?>">
					Выберите дату окончания периода: <input type="date" class='center' name="dateTo" value="<?php echo $dateTo ?>" max="<?php echo $today ?>">
					Выберите организацию <select name="organization" id="org-select" class="select">
						<?php switch ($inn):
							case '5024180062': ?>
								<option value="5024180062">Вектор</option>
								<option value="5024163243">Спектр</option>
								<? break; ?>
							<?php
							case '5024163243': ?>
								<option value="5024163243">Спектр</option>
								<option value="5024180062">Вектор</option>
								<? break; ?>
							<?php
							case '': ?>
								<option value="5024163243">Спектр</option>
								<option value="5024180062">Вектор</option>
						<? endswitch; ?>
						<input style="margin-left: 10px" type="submit" value="Отправить">
				</p>
			</form>
		</div>
	</div>
	<div class="base">
		<div class="contentMain">
			<table class="iksweb">
				<tbody>
					<tr>
						<td>
							<div style="width: 900px; height: 400px; overflow-y: scroll;" >
								<canvas id="test">
							</div>
						</td>
						<td>
							<div style="width: 900px; height: 400px">
								<table class="iksweb">
									<tbody>
										<tr>
											<td style="font-size: 25px;">
												Средний пробег с грузом: 5927 км
											</td>
											<td style="font-size: 25px;">
												Средний пробег без груза: 9210 км
											</td>
										</tr>
										<tr>
											<td style="font-size: 25px;">
												Среднее количество рейсов: 92
											</td>
											<td style="font-size: 25px;">
												Средний КПД рейсов: 44
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div>
			<div class="contentMain">
				<table class="baseTable">
					<tr>
						<td>
							<table class="iksweb" style="margin-left: 10px; width: 98%">
								<thead>
									<tr>
										<th colspan="10" style="border-bottom: solid; background-color: #14611794;">Топ лучших</th>
									</tr>
									<tr>
										<th style="background-color: #14611794;">Номер</th>
										<th colspan="2" style="background-color: #14611794;">Пробег по GPS</th>
										<th colspan="2" style="background-color: #14611794;">Пробег с грузом, км</th>
										<th colspan="2" style="background-color: #14611794;">Количество рейсов</th>
										<th colspan="2" style="background-color: #14611794;">КПД по пробегу</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="width:250px">
											Е 354 АХ 790
										</td>
										<td style="border-right:none;">
											945032
										</td>
										<td style="border-left:none;">
											+ 1851
										</td>
										<td style="border-right:none">
											345032 км
										</td>
										<td style="border-left:none;">
											+ 8148
										</td>
										<td style="border-right:none">
											55
										</td>
										<td style="border-left:none;">
											+ 17
										</td>
										<td style="border-right:none">
											47
										</td>
										<td style="border-left:none;">
											+ 2
										</td>
									</tr>
									<tr>
										<td style="width:250px">
											Е 354 АХ 790
										</td>
										<td style="border-right:none;">
											945032
										</td>
										<td style="border-left:none;">
											+ 1851
										</td>
										<td style="border-right:none">
											345032 км
										</td>
										<td style="border-left:none;">
											+ 8148
										</td>
										<td style="border-right:none">
											55
										</td>
										<td style="border-left:none;">
											+ 17
										</td>
										<td style="border-right:none">
											47
										</td>
										<td style="border-left:none;">
											+ 2
										</td>
									</tr>
									<tr>
										<td style="width:250px">
											Е 354 АХ 790
										</td>
										<td style="border-right:none;">
											945032
										</td>
										<td style="border-left:none;">
											+ 1851
										</td>
										<td style="border-right:none">
											345032 км
										</td>
										<td style="border-left:none;">
											+ 8148
										</td>
										<td style="border-right:none">
											55
										</td>
										<td style="border-left:none;">
											+ 17
										</td>
										<td style="border-right:none">
											47
										</td>
										<td style="border-left:none;">
											+ 2
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td>
							<table class="iksweb" style="margin-right: 10px; width: 98%">
								<thead>
									<tr>
										<th colspan="10" style="border-bottom: solid; background-color: #fc0d0d94;">Топ худших</th>
									</tr>
									<tr>
										<th style="background-color: #fc0d0d94;">Номер</th>
										<th colspan="2" style="background-color: #fc0d0d94;">Пробег по GPS</th>
										<th colspan="2" style="background-color: #fc0d0d94;">Пробег с грузом, км</th>
										<th colspan="2" style="background-color: #fc0d0d94;">Количество рейсов</th>
										<th colspan="2" style="background-color: #fc0d0d94;">КПД по пробегу</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="width:250px">
											Е 354 АХ 790
										</td>
										<td style="border-right:none;">
											945032
										</td>
										<td style="border-left:none;">
											- 1851
										</td>
										<td style="border-right:none">
											345032 км
										</td>
										<td style="border-left:none;">
											- 8148
										</td>
										<td style="border-right:none">
											55
										</td>
										<td style="border-left:none;">
											- 17
										</td>
										<td style="border-right:none">
											47
										</td>
										<td style="border-left:none;">
											- 2
										</td>
									</tr>
									<tr>
										<td style="width:250px">
											Е 354 АХ 790
										</td>
										<td style="border-right:none;">
											945032
										</td>
										<td style="border-left:none;">
											- 1851
										</td>
										<td style="border-right:none">
											345032 км
										</td>
										<td style="border-left:none;">
											- 8148
										</td>
										<td style="border-right:none">
											55
										</td>
										<td style="border-left:none;">
											- 17
										</td>
										<td style="border-right:none">
											47
										</td>
										<td style="border-left:none;">
											- 2
										</td>
									</tr>
									<tr>
										<td style="width:250px">
											Е 354 АХ 790
										</td>
										<td style="border-right:none;">
											945032
										</td>
										<td style="border-left:none;">
											- 1851
										</td>
										<td style="border-right:none">
											345032 км
										</td>
										<td style="border-left:none;">
											- 8148
										</td>
										<td style="border-right:none">
											55
										</td>
										<td style="border-left:none;">
											- 17
										</td>
										<td style="border-right:none">
											47
										</td>
										<td style="border-left:none;">
											- 2
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div>
			<div class="contentMain">
			<? for ($i = 0; $i < 5; $i++) : ?>
				<ul>
				<li><?php print_r($dataArray['Пробег с грузом'][$i]['ТС']) ?></li>
				<li><?php print_r($dataArray['Пробег с грузом'][$i]['Значение']) ?></li>
				</ul>
				<? endfor; ?>
			</div>
		</div>
	</div>
</body>

<script>
	Chart.defaults.font.size = 13;
	ctx = document.getElementById('test');
	new Chart(ctx, {
		type: 'pie',
		data: {
			labels: [<?php echo $labels ?>],
			datasets: [

				{
					label: 'Тягачи',
					data: [<?php echo $planProfitVal ?>],
					backgroundColor: [
						'rgba(75, 192, 192, 0.3)',
						'rgb(174, 252, 187)',
						'rgb(247, 209, 173)',
					],
					borderColor: [
						'rgba(0, 0, 0, 0.3)',
					],
					borderWidth: 1,
					datalabels: {
						formatter: (value, ctx) => {

							return 'Тягачи: ' + "\n" + String(value);
						},
						textAlign: 'center'
					}
				},
				{
					label: 'Прицепы',
					data: [<?php echo $profitVal ?>],
					backgroundColor: [
						'rgba(75, 192, 192, 0.3)',
						'rgb(174, 252, 187)',
						'rgb(247, 209, 173)',
					],
					borderColor: [
						'rgba(0, 0, 0, 0.3)',
					],
					borderWidth: 1,
					datalabels: {
						formatter: (value, ctx) => {
							let plan1 = ctx.chart.data.datasets[0].data[ctx.dataIndex];
							let planProfit1 = ctx.chart.data.datasets[1].data[ctx.dataIndex];

							percent = 'Прицепы: ' + "\n" + String(value);
							return percent;
						},
						font: {
							// weight: 'bold',
						},
						textAlign: 'center'
					},
				},
			],
		},
		options: {
			maintainAspectRatio: false,

			scales: {
				x: {},
				y: {
					beginAtZero: true
				}
			},
			plugins: {
				legend: {
					position: 'top',
				},

			}
		},
		plugins: [ChartDataLabels]
	});
</script>
<?php require_once $_SERVER['DOCUMENT_ROOT'] .  '/templates/footer.php'; ?>

</html>
<table class="headTable">
			<thead>
				<tr>
					<th style="text-align: center">Госномер</th>
					<th style="text-align: center">Рейсов</th>
					<th style="text-align: center">ГСМ</th>
					<th style="text-align: center">Запчасти</th>
					<th style="text-align: center">Шины</th>
				</tr>
			</thead>
			<? foreach ($allData1['dataTrailer'] as $item) : ?>
				<tbody>
					<td style="text-align: center">
						<?php print_r($item['ТС']) ?>
					</td>
					<td style="text-align: center">
						<?php print_r($item['КолРейсов']) ?>
					</td>
					<td style="text-align: center">
						<?php print_r($item['ГСМ']) ?>
					</td>
					<td style="text-align: center">
						<?php print_r($item['Запчасти']) ?>
					</td>
					<td style="text-align: center">
						<?php print_r($item['Шины']) ?>
					</td>
				</tbody>
			<? endforeach; ?>
		</table>