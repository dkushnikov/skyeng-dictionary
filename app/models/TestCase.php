<?php


namespace app\models;

class TestCase
{
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
    }
}