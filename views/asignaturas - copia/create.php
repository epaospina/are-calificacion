<?php

/**********
Versión: 001
Fecha: 09-03-2018
Desarrollador: Oscar David Lopez
Descripción: CRUD de Asignaturas
---------------------------------------
Modificaciones:
Fecha: 09-03-2018
Persona encargada: Oscar David Lopez
Cambios realizados: - Cambio en la miga de pan y envio de variables al _form
---------------------------------------
**********/
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Asignaturas */

$this->title = 'Agregar';
$this->params['breadcrumbs'][] = ['label' => 'Asignaturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asignaturas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'estados'=>$estados,
		'sedes'=>$sedes,
    ]) ?>

</div>