<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/**
 * @var string $examinee
 * @var int    $pointsNum
 */

$this->title                   = 'Test results';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-test-results">
    <div class="jumbotron">
        <h1>Congratulations, <?= Html::encode($examinee) ?>!</h1>

        <p class="lead">You've got <?= $pointsNum ?> point(s)</p>


        <?php $form = ActiveForm::begin(['action' => ['cancel-test']]); ?>
        <div class="row">
            <?= Html::hiddenInput('startNew', '1') ?>
            <?= Html::submitButton('Try again', ['class' => 'btn btn-info']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
