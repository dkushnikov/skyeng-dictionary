<?php

namespace app\controllers;

use app\models\StartTestForm;
use app\models\TestSuite;
use Yii;
use yii\base\UserException;
use yii\web\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    const APP_PARAMS__DICTIONARY = 'dictionary';

    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::className(),
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if ($this->getSession()->get(self::SESSION__HAS_TEST_SUITE, false)) {

            return $this->redirect(['process-test']);
        }

        return $this->render('index');
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionStartTest()
    {
        $model = new StartTestForm();

        if ($model->load($this->getRequest()->post()) && $model->validate()) {

            $dictionary = \Yii::$app->params[self::APP_PARAMS__DICTIONARY];
            $testSuite  = new TestSuite($model->name, $dictionary);

            $this->getSession()->set(self::SESSION__HAS_TEST_SUITE, true);
            $this->getSession()->set(self::SESSION__TEST_SUITE, serialize($testSuite));

            return $this->redirect(['process-test']);
        }

        return $this->render('start-test',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @return string|\yii\web\Response
     * @throws UserException
     */
    public function actionProcessTest()
    {
        $testSuite = $this->getTestSuite();

        if ($this->getRequest()->post()) {

            $answer = $this->getRequest()->post('answer');
            if ($testSuite->getCurrentCase()->validate($answer)) {


                $testSuite->markSuccessful();
            } elseif ($testSuite->getCurrentCase()->hasMoreAttempts()) {

                $this->getSession()->setFlash(self::SESSION__TEST_CASE_ERROR, true);
            } else {

                $testSuite->markFailed();
            }

            $this->getSession()->set(self::SESSION__TEST_SUITE, serialize($testSuite));

            return $this->refresh();

        }

        if ($testSuite->hasMoreMistakes() && $testSuite->getCurrentCase()) {
            return $this->render('process-test',
                [
                    'examinee' => $testSuite->getExaminee(),
                    'case'     => $testSuite->getCurrentCase(),
                    'position' => $testSuite->getCurrentPosition(),
                    'totalNum' => $testSuite->getTotalCasesNum(),
                ]
            );
        }

        return $this->redirect(['test-results']);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionCancelTest()
    {
        if ($this->getRequest()->post()) {
            $startNew = $this->getRequest()->post('startNew', false);

            $this->getSession()->set(self::SESSION__HAS_TEST_SUITE, false);
            $this->getSession()->set(self::SESSION__TEST_SUITE, null);

            return $this->redirect($startNew ? ['start-test'] : ['index']);
        }


        return $this->redirect(['index']);
    }

    /**
     * @return string
     * @throws UserException
     */
    public function actionTestResults()
    {
        $testSuite = $this->getTestSuite();

        return $this->render('test-results',
            [
                'examinee'  => $testSuite->getExaminee(),
                'pointsNum' => $testSuite->getPointsNum(),

            ]
        );
    }

    const SESSION__HAS_TEST_SUITE  = 'hasTestSuite';
    const SESSION__TEST_SUITE      = 'testSuite';
    const SESSION__TEST_CASE_ERROR = 'testCaseError';

    /**
     * @return \yii\web\Session
     */
    private function getSession()
    {

        return \Yii::$app->getSession();
    }

    /**
     * @return \yii\web\Request
     */
    private function getRequest()
    {

        return \Yii::$app->getRequest();
    }


    /**
     * @return mixed
     * @throws UserException
     */
    private function getTestSuite()
    {
        $testSuite = unserialize($this->getSession()->get(self::SESSION__TEST_SUITE, null));

        if (!($testSuite instanceof TestSuite)) {
            throw new UserException('Can\'t restore test suite');
        }

        return $testSuite;
    }
}
