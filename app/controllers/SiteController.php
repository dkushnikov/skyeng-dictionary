<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\StartTestForm;
use app\models\TestSuite;
use Yii;
use yii\base\UserException;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        if ($this->getSession()->get(self::SESSION__HAS_TEST_SUITE, false)) {

            return $this->redirect(['process-test']);
        }

        return $this->render('index');
    }

    const APP_PARAMS__DICTIONARY = 'dictionary';

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

    public function actionProcessTest()
    {
        $testSuite = unserialize($this->getSession()->get(self::SESSION__TEST_SUITE, null));

        if (!($testSuite instanceof TestSuite)) {
            throw new UserException('Something go wrong');
        }

        if ($this->getRequest()->post()) {

            VarDumper::dump($testSuite->getCurrentCase());

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

    public function actionTestResults()
    {
        $testSuite = unserialize($this->getSession()->get(self::SESSION__TEST_SUITE, null));

        if (!($testSuite instanceof TestSuite)) {
            throw  new UserException('Something go wrong');
        }

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
    public function getSession()
    {

        return \Yii::$app->getSession();
    }

    /**
     * @return \yii\web\Request
     */
    public function getRequest()
    {

        return \Yii::$app->getRequest();
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login',
            [
                'model' => $model,
            ]
        );
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
