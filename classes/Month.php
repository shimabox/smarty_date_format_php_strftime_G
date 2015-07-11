<?php
/**
 * Month
 *
 * @author shimabox
 */
class Month
{
    /**
     * Week
     * @var array [\Week]
     */
    protected $weeks = array();

    /**
     * 年
     * @var string
     */
    protected $year = "";

    /**
     * 月
     * @var string
     */
    protected $month = "";

    /**
     * 月曜日基準にするか
     * @var boolean
     */
    protected $beginMonday = false;

    /**
     * 指定された年月の最初の日付の曜日コード
     * @var string
     */
    protected $firstDayWeekCode;

    /**
     * 指定された年月の最後の日付
     * @var string
     */
    protected $lastDay;

    /**
     * DateTime
     * @var \DateTime
     */
    protected $dateTime = null;

    /**
     * コンストラクタ
     *
     * @param string $date 日付('Ym') 空だと当月をセットする
     * @param boolean $beginMonday 月曜日基準にする
     */
    public function __construct($date="", $beginMonday=false)
    {
        // 日付をセット
        $this->settingDate($date);

        // 月曜日基準にする
        if ($beginMonday) {
            $this->setBeginMonday(true);
        }
    }

    /**
     * 生成
     *
     * @param string $date 日付('Ym') 空だと当月をセットする
     * @param boolean $beginMonday 月曜日基準にする
	 *
     * @return \Month
     */
    public static function forge($date="", $beginMonday=false)
    {
        return new static($date, $beginMonday);
    }

    /**
     * 指定された年月をセットする
     *
     * @param string $date
	 *
     * @return \Month
     */
    public function settingDate($date="")
    {
        $timestamp = strtotime($date . "01");

        if (is_numeric($timestamp)) { // 性善説
            $month = date('m', $timestamp);
            $year  = date('Y', $timestamp);
        } else {
            $month = date('m');
            $year  = date('Y');
        }

        $this->month = $month;
        $this->year  = $year;

        return $this;
    }

    /**
     * 月曜日基準にするかどうかのセッタ
     *
     * @param boolean $in
	 *
     * @return \Month
     */
    public function setBeginMonday($in)
    {
        $this->beginMonday = $in;
        return $this;
    }

    /**
     * 処理準備<br>
     * beginMondayなどの設定は終わっている想定
     *
     * @return \Month
     */
    public function prepare()
    {
        $this->dateTime = new DateTime();
        $this->firstDayWeekCode = $this->firstDayWeekCode();
        $this->lastDay = $this->lastDay();

        return $this;
    }

    /**
     * 計算
     *
     * @return \Month
     */
    public function calc()
    {
        $day = 1;
        // 先月用
        $lastMonthLastDay = $this->lastMonthLastDay() - $this->firstDayWeekCode;
        // 来月開始日用
        $nextMonthDay = 1;
        $weekCnt = $this->haveWeeksCount();

        for ($i=1;$i<=$weekCnt;$i++) {

            $week = new Week();

            for ($j=0;$j<7;$j++) {

                // 先月
                if ($i===1 && $j < $this->firstDayWeekCode) {
                    $week->setDays(
                        Day::forge(
                            $this->lastMonthFullDate($lastMonthLastDay + $j + 1),
                            Day::LAST_MONTH
                        )->calc()
                    );
                    continue;
                }

                // 当月
                if ($day <= $this->lastDay) {
                    $week->setDays(Day::forge($this->fullDate($day++))->calc());
                    continue;
                }

                // 来月
                $week->setDays(
                    Day::forge(
                        $this->nextMonthFullDate($nextMonthDay++),
                        Day::NEXT_MONTH
                    )->calc()
                );
            }

            $this->weeks[$i] = $week;
        }

        return $this;
    }

    /**
     * 週を返す
     *
     * @return array
     */
    public function getWeeks()
    {
        return $this->weeks;
    }

    /**
     * 持っている週の数を返す
     * @return int
     */
    public function getWeeksCount()
    {
        return count($this->weeks);
    }

    /**
     * 持っている週の数
     *
     * @return int
     */
    protected function haveWeeksCount()
    {
        return (int)ceil(($this->firstDayWeekCode + $this->lastDay) / 7) ;
    }

    /**
     * 指定された年月の最初の日付は何曜日かコードを返す
     *
     * @return string $this->beginMonday === false : 日曜日が 0 月曜日が1 ... 土曜日が 6<br>
     *                $this->beginMonday === true  : 日曜日が 6 月曜日が0 ... 土曜日が 5
     */
    protected function firstDayWeekCode()
    {
        return $this->dateTime
                    ->setDate($this->year, $this->month, $this->beginMonday ? 0 : 1)
                    ->format('w');
    }

    /**
     * 指定された年月の最後の日付を返す
     *
     * @return string
     */
    protected function lastDay()
    {
        return $this->dateTime
                    ->setDate($this->year, $this->month, 1)
                    ->format('t');
    }

    /**
     * 指定された年月の先月の最終日を返す
     *
     * @return string
     */
    protected function lastMonthLastDay()
    {
        return $this->dateTime
                    ->setDate($this->year, $this->month, -1)
                    ->format('t');
    }

    /**
     * 当月の日付を返す(timestamp)
     *
     * @param string $day
	 *
     * @return int
     */
    protected function fullDate($day)
    {
        $dt = $this->dateTime->setDate($this->year, $this->month, $day);
        return strtotime($dt->format('Ymd'));
    }

    /**
     * 先月の日付を返す(timestamp)
     *
     * @param string $day
     *
     * @return int
     */
    protected function lastMonthFullDate($day)
    {
        $dt = $this->dateTime
                    ->setDate($this->year, $this->month, 1)
                    ->modify('-1 month');

        return strtotime($dt->format('Ym') . sprintf('%02d', $day));
    }

    /**
     * 来月の日付を返す(timestamp)
     *
     * @param string $day
     *
     * @return int
     */
    protected function nextMonthFullDate($day)
    {
        $dt = $this->dateTime
                    ->setDate($this->year, $this->month, 1)
                    ->modify('+1 month');

        return strtotime($dt->format('Ym') . sprintf('%02d', $day));
    }
}