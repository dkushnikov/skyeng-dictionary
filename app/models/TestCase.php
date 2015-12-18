<?php


namespace app\models;

class TestCase
{
    const MAX_ATTEMPTS_NUM = 2;


    private $question;
    private $answer;

    private $variants = [];

    private $attemptsNum = 0;

    /**
     * TestCase constructor.
     *
     * @param       $question
     * @param       $answer
     * @param array $variants
     */
    public function __construct($question, $answer, array $variants)
    {
        $this->question = $question;
        $this->answer   = $answer;

        $this->variants = $variants;
        array_push($this->variants, $answer);
        shuffle($this->variants);
    }

    public function validate($answer)
    {

        $this->attemptsNum++;

        return $answer === $this->answer;
    }

    /**
     * @return array
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    public function hasMoreAttempts()
    {
        return $this->attemptsNum < self::MAX_ATTEMPTS_NUM;
    }

}