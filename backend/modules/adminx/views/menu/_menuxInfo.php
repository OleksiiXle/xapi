<?php
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'parent_id',
        'sort',
        [
            'label' => \Yii::t('app', 'Название'),
            'value' => function () use ($model){
                return \Yii::t('app', $model->name);
            },
            'format' => 'html',
        ],

        'route',
        'role',
        [
            'label' => \Yii::t('app', 'Уровень пользователя'),
            'value' => function () use ($model){
                return \app\modules\adminx\models\MenuXX::ACCESS_LEVEL_DICT[$model->access_level];
            },
            'format' => 'html',
        ],

    ],
]);
?>
