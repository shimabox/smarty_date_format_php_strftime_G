<?php
/**
 * Week<br>
 * Monthから使われる
 *
 * @author shimabox
 */
class Week
{
    /**
     * 自身が持つ日付
     * @var array [\Day]
     */
    protected $days = array();

    /**
     * コンストラクタ
     *
     * @param Day $day
     */
    public function __construct(Day $day=null)
    {
        if ($day !== null) {
            $this->days[] = $day;
        }
    }

    /**
     * 日付 セッタ
     *
     * @param Day $day
     *
     * @return \Week
     */
    public function setDays(Day $day)
    {
        $this->days[] = $day;
        return $this;
    }

    /**
     * 日付 ゲッタ
     *
     * @return array
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * 翌月に含まれる日付の数を返す
     *
     * @return int
     */
    public function includedNextMonthDayCnt()
    {
        $ret = 0;
        foreach ($this->days as $day) {
            if ($day->isIncludedNextMonth()) {
                $ret++;
            }
        }

        return $ret;
    }

    /**
     * 結合して返す
     *
     * @param string $glue
     *
     * @return string
     */
    public function concatDay($glue=",")
    {
        return implode($glue, $this->days);
    }
}