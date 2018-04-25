<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PerfilesPersonasInstitucion */

$this->title = 'Agregar Perfiles Personas Institucion';
$this->params['breadcrumbs'][] = ['label' => 'Perfiles Personas Institucions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perfiles-personas-institucion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'perfilesTable' => $perfilesTable,
		'perfiles' => $perfiles,
		'instituciones' => $instituciones,
		'estados' => $estados,
    ]) ?>

</div>
