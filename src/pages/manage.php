<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
}
$inn = '';
$today = date("Y-m-d");
$dateTo = date("Y-m-d");
$dateFrom = date("Y-m-d", strtotime($dateTo . '- 7 days'));
$message = '';
$status = 0;

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
}

$groupData = getGroupData($dateFrom, $dateTo, $inn);
$allData = getAllData($dateFrom, $dateTo, $inn);
?>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; ?>

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
                        <input style="margin-left: 10px" type="submit" value="Сформировать">
                </p>
            </form>
        </div>
    </div>
    <div class="base">
        <div class="contentMain">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/tripCount.php'; ?>
                        </td>
                        <td>
                            <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/avgProfitPerKm.php'; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="contentMain">
            <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/profitPerClient.php'; ?>
        </div>
        <div class="contentMain">
            <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/profitPlan.php'; ?>
        </div>
    </div>
</body>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/templates/footer.php'; ?>

</html>