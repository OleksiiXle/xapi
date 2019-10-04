<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\modules\adminx\models\AuthItem;

switch ($model->type){
    case AuthItem::TYPE_ROLE:
        $this->title = \Yii::t('app', 'Роль');
        break;
    case AuthItem::TYPE_PERMISSION:
        $this->title = \Yii::t('app', 'Разрешение');
        break;
    case AuthItem::TYPE_ROUTE:
        $this->title = \Yii::t('app', 'Маршрут');
        break;

}

?>

<div class="container-fluid">
    <h3><?= Html::encode($this->title) ?></h3>
    <?php
    ?>
    <div class="col-md-3">
        <?php $form = ActiveForm::begin([
            'id' => 'form-update',
        ]); ?>
        <?= Html::errorSummary($model)?>
        <?php
        echo $form->field($model, 'type')->hiddenInput()->label(false);
        echo $form->field($model, 'name');
        echo $form->field($model, 'description');
        echo $form->field($model, 'rule_name')
            ->dropDownList(AuthItem::getRulesList(),
                ['options' => [ $model->rule_name => ['Selected' => true]],]);

        ?>
        <div class="form-group" align="center">
            <?= Html::submitButton(\Yii::t('app', 'Создать'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            <?= Html::a(\Yii::t('app', 'Отмена'), '/adminx/auth-item',[
                'class' => 'btn btn-danger', 'name' => 'reset-button'
            ]);?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-9">
    </div>
</div>


