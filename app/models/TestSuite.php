<?php


namespace app\models;


class TestSuite
{
    private $examinee;

    private $remainWords;
    private $passedWords;

    /** @var  TestCase */
    private $currentCase;

    private $pointsNum = 0;
    private $errorsNum = 0;

    /**
     * TestSuite constructor.
     *
     * @param string $examinee
     */
    public function __construct($examinee)
    {
        $this->examinee = $examinee;
    }

    public function hasMoreCases()
    {
        return !empty($remainWords);
    }

    public function getCurrentCase()
    {
        return $this->currentCase;
    }

    public function markSuccessful()
    {

    }

    public function markFailed()
    {

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


}