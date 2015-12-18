<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\StartTestForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = 'Start test';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-start-test">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        If you want start test case, please fill form and press "Begin" button
    </p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>


            <div class="form-group">
                <?= Html::submitButton('Begin', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
