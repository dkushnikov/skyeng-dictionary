<?php


namespace app\models;


class TestSuite
{
    const CASE_VARIANTS_NUM = 3;
    const MAX_MISTAKES_NUM  = 3;

    private $examinee;

    private $remainWords;
    //private $passedWords;

    private $sources = [];
    private $results = [];

    /** @var  TestCase */
    private $currentCase;

    private $pointsNum   = 0;
    private $mistakesNum = 0;

    /**
     * TestSuite constructor.
     *
     * @param string $examinee
     * @param array  $wordsMap
     */
    public function __construct($examinee, $wordsMap = [])
    {
        assert(is_string($examinee));
        assert(is_array($wordsMap));
        assert(!empty($wordsMap));

        $this->examinee = $examinee;

        foreach ($wordsMap as $source => $result) {
            $this->sources[] = $source;
            $this->results[] = $result;

            $this->remainWords[] = [$source => $result];
        }
        shuffle($this->remainWords);

        $this->ensureNextCase();
    }

    private function ensureNextCase()
    {
        if (empty($this->remainWords)) {
            return;
        }

        $word      = array_pop($this->remainWords);
        $direction = (bool)rand(0, 1);

        if ($direction) {
            shuffle($this->results);
            $case = new TestCase(key($word), current($word), array_slice($this->results, 0, self::CASE_VARIANTS_NUM));
        } else {
            shuffle($this->sources);
            $case = new TestCase(current($word), key($word), array_slice($this->sources, 0, self::CASE_VARIANTS_NUM));
        }

        $this->currentCase = $case;
    }

    public function hasMoreCases()
    {
        return !empty($this->remainWords);
    }

    public function hasMoreMistakes()
    {
        return $this->mistakesNum < self::MAX_MISTAKES_NUM;
    }

    public function getCurrentCase()
    {
        return $this->currentCase;
    }

    public function markSuccessful()
    {
        $this->pointsNum++;
        $this->currentCase = null;

        $this->ensureNextCase();
    }

    public function markFailed()
    {
        $this->mistakesNum++;
        $this->currentCase = null;

        $this->ensureNextCase();
    }

    /**
     * @return string
     */
    public function getExaminee()
    {
        return $this->examinee;
    }

    /**
     * @return int
     */
    public function getPointsNum()
    {
        return $this->pointsNum;
    }

    public function getCurrentPosition()
    {
        return $this->getTotalCasesNum() - count($this->remainWords);

    }

    public function getTotalCasesNum()
    {
        return count($this->sources);
    }

}