<?php

use \yii\helpers\Html;

//\app\components\widgets\menuUpdate\MenuUpdateAssets::register($this);
\app\modules\adminx\assets\AdminxxMenuAsset::register($this);

$this->title = \Yii::t('app', 'Редактор меню');

?>
<div class="container-fluid">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
            <div class="xCard" style="min-height: 300px ">
                <?php
                echo \app\components\widgets\menuUpdate\MenuUpdateWidget::widget([
                    'menu_id' => 'NumberOne',
                    'params' => [
                        'mode' => 'update'
                    ]
                ])
                ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="xCard" style="min-height: 300px ">
                <div id="menuInfo">

                </div>
            </div>
        </div>
</div>

<script>
    $(document).ready ( function(){
        initTrees();
    });

</script>

