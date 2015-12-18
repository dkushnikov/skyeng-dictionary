<?php

use app\controllers\SiteController;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/**
 * @var string               $examinee
 * @var \app\models\TestCase $case
 * @var int                  $position
 * @var int                  $totalNum
 */


$this->title                   = 'Process test';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-process-test">
    <h1><?= Html::encode($this->title) ?> :: <?= Html::encode($examinee) ?> (<?= $position ?>/<?= $totalNum ?>)</h1>

    <?php if (Yii::$app->getSession()->hasFlash(SiteController::SESSION__TEST_CASE_ERROR)) { ?>

        <div class="alert alert-danger">
            Wrong. Try again.
        </div>

    <?php } ?>


    <h3><?= $case->getQuestion() ?></h3>

    <p>
        Select right translation:
    </p>

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <?php foreach ($case->getVariants() as $variant) { ?>
                <div class="pull-left">
                    <?= Html::submitButton($variant, ['class' => 'btn', 'name' => 'answer', 'value' => $variant]) ?>
                    <span>&nbsp;</span>
                </div>


            <?php } ?>
        </div>
        <?php ActiveForm::end(); ?>

        <hr>

        <div class="row">
            <?php $form = ActiveForm::begin(['action' => ['cancel-test']]); ?>
            <?= Html::submitButton('Cancel test', ['class' => 'btn btn-danger']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
