<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
if (empty($_SESSION['user_id'])) {
	header('Location: /login');
}

$inn = '';
$period = 'week';
$today = date("Y-m-d");
$dateTo = date("Y-m-d");
$dateFrom = date("Y-m-d", strtotime($dateTo . '- 7 days'));
$message = '';
$status = 0;
$pastDateFrom = date("Y-m-d", strtotime('- 1' . $period, strtotime($dateFrom)));
$pastDateTo = date("Y-m-d", strtotime('- 1' . $period, strtotime($dateTo)));

if (isset($_POST)) {
	if (isset($_POST['dateFrom'])) {
		$dateFrom = $_POST['dateFrom'];
	}
	if (isset($_POST['dateTo'])) {
		$dateTo = $_POST['dateTo'];
	}
	if ($dateFrom > $dateTo) {
		$status = 1;
		$message = 'Начальная дата не может быть больше конечной!';
		$dateFrom = $dateTo;
	}
	if (isset($_POST['organization'])) {
		$inn = $_POST['organization'];
		setcookie("inn", $inn);
	}
	if (isset($_POST['category'])) {
		$category = $_POST['category'];
	}
	if (isset($_POST['opiuDate'])) {
		$opiuDate = $_POST['opiuDate'];
		$month = date("m", strtotime($opiuDate));
		$year = date('Y', strtotime($opiuDate));
		$dateFrom = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
		$dateTo = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
	}
	if (isset($_POST['period'])) {
		$period = $_POST['period'];
		$pastDateFrom = date("Y-m-d", strtotime('- 1' . $period, strtotime($dateFrom)));
		$pastDateTo = date("Y-m-d", strtotime('- 1' . $period, strtotime($dateTo)));
		setcookie("period", $period);
	}
}

//для диаграммы периоды
$allDataCurrent = getLogisticData($dateFrom, $dateTo);
$allDataCurrentResult = allSpent($allDataCurrent);
$allDataPast = getLogisticData($pastDateFrom, $pastDateTo);
$allSpentLabels = getValue($allDataCurrentResult);
$allSpentPast = allSpent($allDataPast);
$pastallSpentLabels = getValue($allSpentPast);
$labelsBarChart = getName($allDataCurrentResult);

//для диаграммы бублик
$doughnutData = trueDataDash(allSpent($allDataCurrent));
arsort($doughnutData);
$doughnutVal = getValue($doughnutData);
$doughnutLabels = getName($doughnutData);
$total = array_sum($doughnutData);


$testTruck = trueDataLogistic($allDataCurrent, 'dataTracktor');
$testTrailer = $allDataCurrent['dataTrailer'];

$traceWithCargo = traceWithCargo($testTruck);
$gsm = getGsm($testTruck);
$repair = getRepair($testTruck);

$wheels = getWheels($testTruck);
$numbers = getCarNumber($gsm, $traceWithCargo, $repair, $wheels);
$allSpentPerCar = allSpentPerCar($numbers, $gsm, $traceWithCargo, $repair, $wheels);

$repairTrailer = getRepair($testTrailer);
$wheelsTrailer = getWheels($testTrailer);
$numbersTrailer = getTrailerNumber($repairTrailer, $wheelsTrailer);
$allSpentPerTrailer = allSpentPerTrailer($numbersTrailer, $repairTrailer, $wheelsTrailer);

if (!empty($gsm)) {
	$avgGsm = round(array_sum($gsm) / count($gsm), 2);
} else {
	$avgGsm = 0;
}
if (!empty($repair)) {
	$avgRepair = round(array_sum($repair) / count($repair), 2);
} else {
	$avgRepair = 0;
}
if (!empty($wheels)) {
	$avgWheels = round(array_sum($wheels) / count($wheels), 2);
} else {
	$avgWheels = 0;
}
if (!empty($allSpentPerCar)) {
	$avgAllSpentPerCar = round(array_sum($allSpentPerCar) / count($allSpentPerCar), 2);
} else {
	$avgAllSpentPerCar = 0;
}
if (!empty($repairTrailer)) {
	$avgRepairTrailer = round(array_sum($repairTrailer) / count($repairTrailer), 2);
} else {
	$avgRepairTrailer = 0;
}
if (!empty($wheelsTrailer) && isset($wheelsTrailer) && count($wheelsTrailer) > 1) {
	$avgWheelsTrailer = round(array_sum($wheelsTrailer) / count($wheelsTrailer), 2);
} else {
	$avgWheelsTrailer = 0;
}

asort($gsm);
asort($repair);
asort($wheels);
arsort($traceWithCargo);
asort($allSpentPerCar);

asort($repairTrailer);
asort($wheelsTrailer);
asort($allSpentPerTrailer);

$trailerDataArray['Запчасти'] = normalize($repairTrailer);
$trailerDataArray['Шины'] = normalize($wheelsTrailer);
$trailerDataArray['Расходы'] = normalize($allSpentPerTrailer);

$dataArray['Пробег с грузом'] = normalize($traceWithCargo);
$dataArray['ГСМ'] = normalize($gsm);
$dataArray['Запчасти'] = normalize($repair);
$dataArray['Шины'] = normalize($wheels);
$dataArray['Расходы'] = normalize($allSpentPerCar);

function strCount($array)
{
	$count = [];
	if (isset($array['Пробег с грузом']) && count($array['Пробег с грузом']) <= 6) {
		$count[] = count($array['Пробег с грузом']);
	}
	if (isset($array['ГСМ']) && count($array['ГСМ']) <= 6) {
		$count[] = count($array['ГСМ']);
	}
	if (isset($array['Запчасти']) && count($array['Запчасти']) <= 6) {
		$count[] = count($array['Запчасти']);
	}
	if (isset($array['Шины']) && count($array['Шины']) <= 6) {
		$count[] = count($array['Шины']);
	}
	if (isset($array['Расходы']) && count($array['Расходы']) <= 6) {
		$count[] = count($array['Расходы']);
	}
	if ($count === [] || max($count) == 0) {
		return 6;
	} else {
		return max($count);
	}
}
$strCountTruck = strCount($dataArray);
$strCountTrailer = strCount($trailerDataArray);
?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; ?>
<style>
	/* Стили таблицы (IKSWEB) */
	table.iksweb {
		text-decoration: none;
		border-collapse: collapse;
		width: 99%;
		text-align: center;
		padding: 5px;
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
		border: 1px solid #354251;
		text-align: center;
	}

	table.iksweb tr:hover {
		background-color: #f9fafb
	}

	table.iksweb tr:hover td {
		color: #354251;
		cursor: default;
	}

	table.baseTable {
		text-decoration: none;
		border-collapse: collapse;
		width: 99%;
		text-align: center;
		margin: auto;
	}

	table.headTable {
		text-decoration: none;
		border-collapse: collapse;
		text-align: center;
		padding: 5px;
		margin-bottom: 10px;
		margin-right: 10px;
	}

	table.headTable td {
		font-size: 13px;
		color: #354251;
		text-align: center;
		width: 300px;
	}

	table.headTable td,
	table.headTable th {
		padding: 5px;
		line-height: 13px;
		border: 1px solid #354251;
		text-align: center;
		/* width: 20%; */
		height: 15px;
		font-family: Helvetica, Arial, sans-serif;
	}

	table.headTable th {
		font-weight: normal;
		font-size: 14px;
		color: #ffffff;
		background-color: #38376ec3;
		text-align: center;
	}

	.chart4 {
		height: 400px;
		max-height: 200px;
	}
</style>

<body>
	<div class="block">
		<div class="mainFilter">
			<form style="margin-left: 10px" method="POST">
				<p style="margin: 10px">Тип сравнения: <select name="period" style="width: 230px;">
						<option value="week" <?php if ($period == "week") {
													echo 'selected';
												} ?>>С прошлой неделей</option>
						<option value="month" <?php if ($period == "month") {
													echo 'selected';
												} ?>>С прошлым месяцем</option>
						<option value="year" <?php if ($period == "year") {
													echo 'selected';
												} ?>>С прошлм годом</option>
					</select>
					Выберите дату начала периода: <input type="date" style="width: 150px;" class='center' name="dateFrom" value="<?php echo $dateFrom ?>" max="<?php echo $dateTo ?>">
					Выберите дату окончания периода: <input type="date" style="width: 150px;" class='center' name="dateTo" value="<?php echo $dateTo ?>" max="<?php echo $today ?>">
					Выберите организацию <select name="organization" id="org-select" class="select" style="width: 120px;">
						<option value="5024180062" <?php if ($inn == "5024180062") {
														echo 'selected';
													} ?>>Вектор</option>
						<option value="5024163243" <?php if ($inn == "5024163243") {
														echo 'selected';
													} ?>>Спектр</option>
					</select>
					<input style="margin-left: 5px; width: 120px;" type="submit" value="Отправить">
				</p>
			</form>
		</div>
	</div>
	<div class="base">
		<div class="contentMain">
			<table>
				<tbody>
					<th style="text-align: center;">
						<p>Затраты по направлениям</p>
					</th>
					<th style="text-align: center;">
						<p>Затраты по направлениям (в сравнении с прошлым периодом)</p>
					</th>
					<tr>
						<td style="width: 50%;">
							<div>
								<div style="margin-top: 10px;">
									<canvas id="test2" style="border-right: solid #e3e1e1; width:100%; height: 500px; padding-right: 10px; padding: 10px">
								</div>
							</div>
						</td>
						<td style="width: 50%;">
							<div class="subbox">
								<canvas id="test" style=" width:100%; height: 550px; margin-left: 10px; margin-right: 10px">
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="contentMain">
			<? if (empty($testTruck) || (empty($dataArray['Пробег с грузом']) && empty($dataArray['ГСМ']) && empty($dataArray['Запчасти']) && empty($dataArray['Шины']) && empty($dataArray['Расходы']))) : ?>
				<p>Данные по тягачам отсутствуют</p>
			<? else : ?>
				<p>Тягачи</p>
				<table class="baseTable">
					<tr>
						<td style="width: 50%;">
							<div>
								<table class="headTable">
									<tr>
										<th style="text-align: center">Ср. затраты на ГСМ</th>
										<th style="text-align: center">Ср. затраты на запчасти</th>
									</tr>
									<tr>
										<td style="border-right:none; width: 500px">
											<?= $avgGsm ?> руб
										</td>
										<td style="width: 500px">
											<?= $avgRepair ?> руб
										</td>
									</tr>
								</table>
							</div>
						</td>
						<td>
							<table class="headTable">
								<thead>
									<tr>
										<th style="text-align: center">Ср. затраты на шины</th>
										<th style="text-align: center">Ср. общая сумма затрат</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="border-right:none; width: 500px">
											<?= $avgWheels  ?> руб
										</td>
										<td style="width: 500px">
											<?= $avgAllSpentPerCar ?> руб
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
				<table class="baseTable">
					<tr>
						<td style="width: 50%;">
							<table class="iksweb">
								<thead>
									<tr>
										<th colspan="10" style="border-bottom: solid; background-color: #14611794;">Топ лучших</th>
									</tr>
									<tr>
										<th colspan="2" style="background-color: #14611794;">Пробег с грузом</th>
										<th colspan="2" style="background-color: #14611794;">Затраты на ГСМ</th>
										<th colspan="2" style="background-color: #14611794;">Затраты на Запчасти</th>
										<th colspan="2" style="background-color: #14611794;">Затраты на шины</th>
										<th colspan="2" style="background-color: #14611794;">Затраты всего</th>
									</tr>
								</thead>
								<tbody>
									<? for ($i = 0; $i < 5; $i++) : ?>
										<tr>
											<? if (count($dataArray['Пробег с грузом']) > $i && count($dataArray['Пробег с грузом']) > 0) : ?>
												<td style="border-right:none">
													<?= $dataArray['Пробег с грузом'][$i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['Пробег с грузом'][$i]['Значение'] ?> км
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
											<? if (count($dataArray['ГСМ']) > $i && count($dataArray['ГСМ']) > 0) : ?>
												<td style="border-right:none;">
													<?= $dataArray['ГСМ'][$i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['ГСМ'][$i]['Значение'] ?> рублей
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
											<? if (count($dataArray['Запчасти']) > $i && count($dataArray['Запчасти']) > 0) : ?>
												<td style="border-right:none;">
													<?= $dataArray['Запчасти'][$i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['Запчасти'][$i]['Значение'] ?> рублей
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
											<? if (count($dataArray['Шины']) > $i && count($dataArray['Шины']) > 0) : ?>
												<td style="border-right:none;">
													<?= $dataArray['Шины'][$i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['Шины'][$i]['Значение'] ?> рублей
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
											<? if (count($dataArray['Расходы']) > $i && count($dataArray['Расходы']) > 0) : ?>
												<td style="border-right:none;">
													<?= $dataArray['Расходы'][$i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['Расходы'][$i]['Значение'] ?> рублей
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
										</tr>
									<? endfor; ?>
								</tbody>
							</table>
						</td>
						<td>
							<table class="iksweb">
								<thead>
									<tr>
										<th colspan="10" style="border-bottom: solid; background-color: #fc0d0d94;">Топ худших</th>
									</tr>
									<tr>
										<th colspan="2" style="background-color: #fc0d0d94;">Пробег с грузом</th>
										<th colspan="2" style="background-color: #fc0d0d94;">Затраты на ГСМ</th>
										<th colspan="2" style="background-color: #fc0d0d94;">Затраты на Запчасти</th>
										<th colspan="2" style="background-color: #fc0d0d94;">Затраты на шины</th>
										<th colspan="2" style="background-color: #fc0d0d94;">Затраты всего</th>
									</tr>
								</thead>
								<tbody>
									<? for ($i = 1; $i < 6; $i++) : ?>
										<tr>
											<? if (count($dataArray['Пробег с грузом']) >= $i && count($dataArray['Пробег с грузом']) > 0) : ?>
												<td style="border-right:none">
													<?= $dataArray['Пробег с грузом'][count($dataArray['Пробег с грузом']) - $i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['Пробег с грузом'][count($dataArray['Пробег с грузом']) - $i]['Значение'] ?> км
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
											<? if (count($dataArray['ГСМ']) >= $i && count($dataArray['ГСМ']) > 0) : ?>
												<td style="border-right:none;">
													<?= $dataArray['ГСМ'][count($dataArray['ГСМ']) - $i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['ГСМ'][count($dataArray['ГСМ']) - $i]['Значение'] ?> рублей
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
											<? if (count($dataArray['Запчасти']) >= $i && count($dataArray['Запчасти']) > 0) : ?>
												<td style="border-right:none;">
													<?= $dataArray['Запчасти'][count($dataArray['Запчасти']) - $i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['Запчасти'][count($dataArray['Запчасти']) - $i]['Значение'] ?> рублей
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
											<? if (count($dataArray['Шины']) >= $i && count($dataArray['Шины']) > 0) : ?>
												<td style="border-right:none;">
													<?= $dataArray['Шины'][count($dataArray['Шины']) - $i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['Шины'][count($dataArray['Шины']) - $i]['Значение'] ?> рублей
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
											<? if (count($dataArray['Расходы']) >= $i && count($dataArray['Расходы']) > 0) : ?>
												<td style="border-right:none;">
													<?= $dataArray['Расходы'][count($dataArray['Расходы']) - $i]['ТС'] ?>
												</td>
												<td style="border-left:none">
													<?= $dataArray['Расходы'][count($dataArray['Расходы']) - $i]['Значение'] ?> рублей
												</td>
											<? else : ?>
												<td style="border-right:none;">
												</td>
												<td style="border-left:none">
												</td>
											<? endif; ?>
										</tr>
									<? endfor; ?>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
		</div>
	<? endif; ?>
	<div class="contentMain">
		<? if (empty($testTrailer) || (empty($trailerDataArray['Запчасти']) && empty($trailerDataArray['Шины']) && empty($trailerDataArray['Расходы']))) : ?>
			<p>Данные по прицепам отсутствуют</p>
		<? else : ?>
			<p>Прицепы</p>
			<table class="baseTable">
				<tr>
					<td style="width: 50%;">
						<div>
							<table class="headTable">
								<tr>
									<th style="text-align: center">Ср. затраты на запчасти</th>
								</tr>
								<tr>
									<td style="width: 1000px">
										<?php print_r($avgRepairTrailer . " руб")  ?>
									</td>
								</tr>
							</table>
						</div>
					</td>
					<td style="width: 50%;">
						<table class="headTable">
							<thead>
								<tr>
									<th style="text-align: center">Ср. затраты на шины</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="width: 1000px">
										<?php print_r($avgWheelsTrailer . " руб") ?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
			<table class="baseTable">
				<tr>
					<td style="width: 50%;">
						<table class="iksweb">
							<thead>
								<tr>
									<th colspan="13" style="border-bottom: solid; background-color: #14611794;">Топ лучших</th>
								</tr>
								<tr>
									<th colspan="2" style="background-color: #14611794;">Затраты на Запчасти</th>
									<th colspan="2" style="background-color: #14611794;">Затраты на шины</th>
									<th colspan="2" style="background-color: #14611794;">Затраты всего</th>
								</tr>
							</thead>
							<tbody>
								<? for ($i = 0; $i < $strCountTrailer; $i++) : ?>
									<tr>
										<? if (count($trailerDataArray['Запчасти']) > $i && count($trailerDataArray['Запчасти']) > 0) : ?>
											<td style="border-right:none">
												<?= $trailerDataArray['Запчасти'][$i]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Запчасти'][$i]['Значение'] ?> руб
											</td>
										<? else : ?>
											<td style="border-right:none;">
											</td>
											<td style="border-left:none">
											</td>
										<? endif; ?>
										<? if (count($trailerDataArray['Шины']) > $i && count($trailerDataArray['Шины']) > 0) : ?>
											<td style="border-right:none">
												<?= $trailerDataArray['Шины'][$i]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Шины'][$i]['Значение'] ?> руб
											</td>
										<? else : ?>
											<td style="border-right:none;">
											</td>
											<td style="border-left:none">
											</td>
										<? endif; ?>
										<? if (count($trailerDataArray['Расходы']) > $i && count($trailerDataArray['Расходы']) > 0) : ?>
											<td style="border-right:none">
												<?= $trailerDataArray['Расходы'][$i]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Расходы'][$i]['Значение'] ?> руб
											</td>
										<? else : ?>
											<td style="border-right:none;">
											</td>
											<td style="border-left:none">
											</td>
										<? endif; ?>
									</tr>
								<? endfor; ?>
							</tbody>
						</table>
					</td>
					<td>
						<table class="iksweb">
							<thead>
								<tr>
									<th colspan="13" style="border-bottom: solid; background-color: #fc0d0d94;">Топ худших</th>
								</tr>
								<tr>
									<th colspan="2" style="background-color: #fc0d0d94;">Затраты на Запчасти</th>
									<th colspan="2" style="background-color: #fc0d0d94;">Затраты на шины</th>
									<th colspan="2" style="background-color: #fc0d0d94;">Затраты всего</th>
								</tr>
							</thead>
							<tbody>
								<? for ($i = 1; $i <= $strCountTrailer; $i++) : ?>
									<tr>
										<? if (count($trailerDataArray['Запчасти']) >= $i && isset($trailerDataArray['Запчасти'][count($trailerDataArray['Запчасти']) - $i])) : ?>
											<td style="border-right:none">
											
											<?= $trailerDataArray['Запчасти'][count($trailerDataArray['Запчасти']) - $i]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Запчасти'][count($trailerDataArray['Запчасти']) - $i]['Значение'] ?> руб
											</td>
										<? elseif (count($trailerDataArray['Запчасти']) >= $i && count($trailerDataArray['Запчасти']) == 1) : ?>
											<td style="border-right:none">
												<?= $trailerDataArray['Запчасти'][0]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Запчасти'][0]['Значение'] ?> руб
											</td>
										<? else : ?>
											<td style="border-right:none;">
											</td>
											<td style="border-left:none">
											</td>
										<? endif; ?>
										<? if (count($trailerDataArray['Шины']) >= $i && isset($trailerDataArray['Шины'][count($trailerDataArray['Шины']) - $i])) : ?>
											<td style="border-right:none">
												<?= $trailerDataArray['Шины'][count($trailerDataArray['Шины']) - $i]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Шины'][count($trailerDataArray['Шины']) - $i]['Значение'] ?> руб
											</td>
										<? elseif (count($trailerDataArray['Шины']) >= $i && count($trailerDataArray['Шины']) == 1) : ?>
											<td style="border-right:none">
												<?= $trailerDataArray['Шины'][0]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Шины'][0]['Шины'] ?> руб
											</td>
										<? else : ?>
											<td style="border-right:none;">
											</td>
											<td style="border-left:none">
											</td>
										<? endif; ?>
										<? if (count($trailerDataArray['Расходы']) >= $i && isset($trailerDataArray['Расходы'][count($trailerDataArray['Расходы']) - $i])) : ?>
											<td style="border-right:none">
												<?= $trailerDataArray['Расходы'][count($trailerDataArray['Расходы']) - $i]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Расходы'][count($trailerDataArray['Расходы']) - $i]['Значение'] ?> руб
											</td>
										<? elseif (count($trailerDataArray['Расходы']) >= $i && count($trailerDataArray['Расходы']) == 1) : ?>
											<td style="border-right:none">
												<?= $trailerDataArray['Расходы'][0]['ТС'] ?>
											</td>
											<td style="border-left:none">
												<?= $trailerDataArray['Расходы'][0]['Значение'] ?> руб
											</td>
										<? else : ?>
											<td style="border-right:none;">
											</td>
											<td style="border-left:none">
											</td>
										<? endif; ?>
									</tr>
								<? endfor; ?>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		<? endif; ?>
	</div>

</body>
<?php require_once $_SERVER['DOCUMENT_ROOT'] .  '/templates/footer.php'; ?>
<script>
	Chart.defaults.font.size = 14;
	ctx = document.getElementById('test');
	new Chart(ctx, {
		type: 'bar',
		data: {
			labels: [<?php echo $labelsBarChart ?>],
			datasets: [{
					label: 'Текущий период',
					barPercentage: 0.8,
					categoryPercentage: 0.8,
					borderRadius: 10,
					data: [<?php echo $allSpentLabels ?>],
					backgroundColor: [
						'rgba(8,64,129, .7)',
					],
					borderColor: [
						'rgba(201, 203, 207)',
					],
					borderWidth: 1,
					datalabels: {
						align: 'center',
						anchor: 'center',
						borderRadius: 10,
						offset: 50,
						padding: 4,
						clamp: false,
						backgroundColor: 'rgba(82,121,167)',
						formatter: (value, ctx) => {
							percent = String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' руб.';
							return percent;
						},
						labels: {
							value: {
								color: 'white'
							}
						}
					}
				},
				{
					label: 'Прошлый период',
					barPercentage: 0.8,
					categoryPercentage: 0.8,
					borderRadius: 10,
					data: [<?php echo $pastallSpentLabels ?>],
					backgroundColor: [
						'rgba(103,193,203, .8)'
					],
					borderColor: [
						'rgba(103,193,203)',
					],
					borderWidth: 1,
					datalabels: {
						align: 'center',
						anchor: 'center',
						offset: 50,
						padding: 4,
						backgroundColor: 'rgba(133,205,213)',
						borderRadius: 10,
						clamp: false,
						formatter: (value, ctx) => {
							percent = String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' руб.';
							return percent;
						},
						font: {
							weight: 520,
						},
						labels: {
							value: {
								color: 'rgba(21, 21, 26, .8)'
							}
						}
					},
				},
			],
		},

		options: {
			maintainAspectRatio: false,
			indexAxis: 'y',
			plugins: {
				legend: {
					position: 'top',
				},
			}
		},
		plugins: [ChartDataLabels]
	});
</script>

<script>
	//customdatalabels
	const customDatalabels = {
		id: 'customDatalabels',
	};
	const total = <?php echo $total ?>;
	const value = [<?php echo $doughnutVal ?>];
	let labelsItems = [<?php echo $doughnutLabels ?>];

	const totalres = String(total).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' руб.';
	const centertextDoughnut = {
		id: 'centertextDoughnut',
		afterDatasetsDraw(chart, args, pluginOptions) {
			const {
				ctx
			} = chart;
			ctx.textAlign = 'center';
			ctx.font = '18px sans-serif';
			const strTotal = 'Сумма затрат: ';
			const text = strTotal + totalres;
			const textWidth = ctx.measureText(text).width;
			const x = chart.getDatasetMeta(0).data[0].x;
			const y = chart.getDatasetMeta(0).data[0].y;
			ctx.fillText(text, x, y);
		}
	};

	for (let i = 0; i < labelsItems.length; i++) {
		let percentageDash = (value[i] / total) * 100;
		labelsItems[i] = percentageDash.toFixed(2) + '%' + ' ' + labelsItems[i];
	}

	Chart.defaults.font.size = 14;
	ctx = document.getElementById('test2');
	new Chart(ctx, {
		type: 'doughnut',
		data: {
			labels: labelsItems,
			datasets: [{
				label: 'Сумма затрат',
				data: [<?php echo $doughnutVal ?>],
				cutout: '80%',
				borderRadius: 15,
				borderWidth: 1,
				padding: 15,
				offset: 25,
				backgroundColor: [
					'rgba(8,64,129, 0.7)',
					'rgba(103,193,203, 0.8)',
					'rgba(56, 55, 110, 0.76)',
					'rgba(119,163,120)',
					'rgba(54, 162, 235, 0.8)',
					'rgba(153, 102, 255, 0.8)',
					'rgba(201, 203, 207, 0.8)',
				],
				borderColor: [
					'rgba(197, 197, 201, 0.76)',
					// 'rgba(103,193,203, 0.8)',
					// 'rgba(56, 55, 110, 0.76)',
					// 'rgba(119,163,120)',
					// 'rgba(54, 162, 235, 0.8)',
					// 'rgba(153, 102, 255, 0.8)',
					// 'rgba(201, 203, 207, 0.8)',
				],
			}]
		},
		options: {
			layout: {
				padding: {
					top: 25,
					left: 15,
					bottom: 25,
				}
			},
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				labels: {
					position: 'outside',
				},
				legend: {
					position: 'right',

				},
				datalabels: {
					align: 'end',
					anchor: 'end',
					// borderRadius: 10,
					offset: 15,
					padding: 5,
					clamp: false,
					formatter: (value, dnct1) => {
						dnct1.chart.data.labels[dnct1.dataIndex];
						result = String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' руб.';
						return result;
					},
					font: {
						weight: 'bold',
					},
					labels: {
						value: {
							color: [
								'black'
							],
						}
					}
				},

			}
		},
		plugins: [ChartDataLabels, centertextDoughnut, customDatalabels],
	});
</script>

</html>