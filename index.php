<?php
error_reporting(-1);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Tokyo'); // 普通はphp.iniでセット

require_once __DIR__ . '/classes/Smarty-3.1.21/libs/Smarty.class.php';
require_once __DIR__ . '/classes/Month.php';
require_once __DIR__ . '/classes/Week.php';
require_once __DIR__ . '/classes/Day.php';

/**
 * Smarty
 *
 * @return \Smarty
 */
function createSmarty()
{
    $smarty = new Smarty();
    $smarty->template_dir = __DIR__ . '/template';
    $smarty->compile_dir = __DIR__ . '/template/compile';
	$smarty->cache_dir = __DIR__ . '/template/cache';
	$smarty->force_compile = false;
	$smarty->caching = true;

    return $smarty;
}

/**
 * 月の情報を返す
 *
 * @param string $baseYear 開始年
 * @param string $targetMonth 対象の月
 * @param boolean $beginMonday 基準を月曜日にするか
 * @param int $loop 何年間分欲しいか
 *
 * @return array
 */
function getMonth($baseYear, $targetMonth, $beginMonday=false, $loop=20)
{
    $ret = array();
    for ($i=0;$i<=$loop;$i++) {
        $month = Month::forge(($baseYear + $i) . $targetMonth, $beginMonday)->prepare()->calc();

        $weekCnt = $month->getWeeksCount();
        $weeks = $month->getWeeks();
        $key = ($baseYear + $i) . '/' . $targetMonth;

        $ret[$key]['days'] = $weeks[$weekCnt]->getDays();
        $ret[$key]['includeNextYear'] = $weeks[$weekCnt]->includedNextMonthDayCnt() > 3;
        $ret[$key]['weekCnt'] = $weekCnt;
    }

    return $ret;
}

// 月曜日基準
$decemberBeginMonday = getMonth(2014, 12, true);
// 日曜日基準
$december = getMonth(2014, 12);

$smarty = createSmarty();
$smarty->assign('decemberBeginMonday', $decemberBeginMonday);
$smarty->assign('december', $december);
$smarty->display('index.tpl');