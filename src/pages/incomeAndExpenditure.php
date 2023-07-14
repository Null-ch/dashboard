<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
}
$DateValue = '';
$inn = '';
$dateTo = '';
$dateFrom = '';
$category = [];
$message = '';
$status = 0;
$statusChart = 0;
$oPiUTotalSring = '';
$date = getPastMounth();

$DateValue = date("Y-m", strtotime($date['dateFrom']));
$maxDate = date("Y-m", strtotime($date['dateFrom']));


if (isset($_POST)) {
    if (isset($_POST['organization'])) {
        $inn = $_POST['organization'];
    }
    if (isset($_POST['category'])) {
        $category = $_POST['category'];
    }
    if (isset($_POST['opiuDate'])) {
        $opiuDate = $_POST['opiuDate'];
        $date = getPastMounth($opiuDate);
        $DateValue = date("Y-m", strtotime($date['dateFrom']));
    }
}

$allOPiU = getGroupData($date['dateFrom'], $date['dateTo'], $inn, 'cashFlows', 'overall');
$groupOPiU = getGroupData($date['dateFrom'], $date['dateTo'], $inn, 'cashFlows', 'dataSheet');

if (isset($allOPiU[0])) {
    $articles = getArticles($allOPiU);
    $oPiUTotalSring = getTotalApiu($groupOPiU);
    $dataIncome = getIncome($allOPiU, $category);
    $dataExpense = getExpense($allOPiU, $category);

    if ($dataIncome) {
        $income = getMoneyMovement($dataIncome);
        arsort($income);
        $incomeNames = getName($income);
        $incomeStats = getValue($income);
    } elseif (!$dataIncome) {
        $statusChart = 1;
    }
    if ($dataExpense) {
        $expense = getMoneyMovement($dataExpense);
        arsort($expense);
        $expenseNames = getName($expense);
        $expenseStats = getValue($expense);
    } elseif (!$dataExpense) {
        $statusChart = 2;
    }
} else {
    $status = 1;
}

?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/header.php'; ?>

<body>
    <div>
        <div class="block">
            <div class="categoryFilter">
                <form method="POST" action="" id="CategoryForm">
                    <p style="margin: 10px">Выберите категорию:
                        <select name="category[]" id="multiple-checkboxes" multiple="multiple">
                            <? foreach ($articles as $key => $item) : ?>
                                <option value="<?= $key ?>">
                                    <li><?= $item ?></li>
                                </option>
                            <? endforeach; ?>
                        </select>
                    </p>
            </div>
            <div class="mainFilter">
                <p style="margin: 10px">
                    Выберите отчетный период: <input type="month" class='center' name="opiuDate" value="<?php print_r($DateValue) ?>" max="<?php print_r($maxDate) ?>">
                    Выберите организацию <select name="organization" id="org-select">
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
    </div>
    <div class="base">
        <?php if ($status == 0) : ?>
            <div class="contentMain">
                <div style="margin-bottom: 5px;">
                    <div>
                        <p><?php print_r($oPiUTotalSring); ?></p>
                    </div>
                </div>
            </div>
            <?php if ($statusChart == 1) : ?>
                <div class="contentMain">
                    <p>По выбранным статьям отсутствуют поступления</p>
                </div>
            <?php else : ?>
                <div class="contentMain">
                    <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/income.php' ?>
                </div>
            <?php endif; ?>
            <?php if ($statusChart == 2) : ?>
                <div class="contentMain">
                    <p>По выбранным статьям отсутствуют выбытия</p>
                </div>
            <?php else : ?>
                <div class="contentMain">
                    <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/expense.php' ?>
                </div>
            <?php endif; ?>

    </div>
    </div> 
<?php elseif ($status == 1) : ?>
    <div class="block" style="margin-bottom: 5px;">
        <strong>Отсутствуют данные за период</strong>
    </div>
<?php endif; ?>

</body>
<script>
    $(document).ready(function() {
        $('#multiple-checkboxes').multiselect({
            nonSelectedText: 'Категории',
            includeSelectAllOption: true,
            search: true,
            maxHeight: 250,
            buttonWidth: 150,
            numberDisplayed: 2,
            nSelectedText: 'selected',
            filterPlaceholder: 'Введите название',
            includeSelectAllDivider: true,
        });
    });
    submitForms = function() {
        document.getElementById("CategoryForm").submit();
        document.getElementById("mainFilterForm").submit();
    }
</script>
<?php require_once $_SERVER['DOCUMENT_ROOT'] .  '/templates/footer.php'; ?>

</html>