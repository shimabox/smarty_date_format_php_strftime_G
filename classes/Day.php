<?php
/**
 * Day<br>
 * Monthから使われてWeekが知っている
 *
 * @author shimabox
 */
class Day
{
    /**
     * 指定された月に含まれる日付
     * @var string
     */
    const THIS_MONTH = 'this';

    /**
     * 指定された月の先月に含まれる日付
     * @var string
     */
    const LAST_MONTH = 'last';

    /**
     * 指定された月の翌月に含まれる日付
     * @var string
     */
    const NEXT_MONTH = 'next';

    /**
     * 日
     * @var string
     */
    protected $day = '';

    /**
     * 曜日
     * @var string
     */
    protected $dayOfWeek = '';

    /**
     * どこに含まれるか識別用
     * @var string
     */
    protected $identifier = '';

    /**
     * 自身が出力された際のフォーマット
     * @var string
     */
    protected $format = 'Y/m/d';

    /**
     * DateTime
     * @var \DateTime
     */
    protected $dateTime = null;

    /**
     * 曜日コードのマッピング
     * @var array
     */
    protected $mappingOfWeekDayCode = array(
        0 => 'sun',
        1 => 'mon',
        2 => 'tue',
        3 => 'wed',
        4 => 'thu',
        5 => 'fri',
        6 => 'sat'
    );

    /**
     * コンストラクタ
     *
     * @param int $day 日付のtimestamp
     * @param string $identifier
     */
    public function __construct($day, $identifier = self::THIS_MONTH)
    {
        $this->day = $day;
        $this->identifier = $identifier;
    }

    /**
     * 生成
     *
     * @param int $day 日付のtimestamp
     * @param string $identifier
	 *
     * @return \Day
     */
    public static function forge($day, $identifier = self::THIS_MONTH)
    {
        return new static($day, $identifier);
    }

    /**
     * 計算
     *
     * @return \Day
     */
    public function calc()
    {
    	$this->dateTime = new DateTime(date('YmdHis', $this->day));

    	// 曜日
        $this->dayOfWeek = $this->dateTime->format('w');

        return $this;
    }

    /**
     * 日付を返す
     *
     * @return int
     */
    public function getDay()
    {
       return $this->day;
    }

    /**
     * 曜日を返す
     *
     * @return int
     */
    public function getDayOfWeek()
    {
        return $this->mappingOfWeekDayCode[$this->dayOfWeek];
    }

    /**
     * 先月に含まれるかどうか
     *
     * @return boolean
     */
    public function isIncludedLastMonth()
    {
        return $this->identifier === self::LAST_MONTH;
    }

    /**
     * 翌月に含まれるかどうか
     *
     * @return boolean
     */
    public function isIncludedNextMonth()
    {
        return $this->identifier === self::NEXT_MONTH;
    }

    /**
     * __toString<br>
     * 自身が出力された際は$this->formatで変換する
     *
     * @return string
     */
    public function __toString()
    {
        return date($this->format, $this->day);
    }
}