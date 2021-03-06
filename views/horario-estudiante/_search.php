<?php
if(@$_SESSION['sesion']=="si")
{ 
	// echo $_SESSION['nombre'];
} 
//si no tiene sesion se redirecciona al login
else
{
	echo "<script> window.location=\"index.php?r=site%2Flogin\";</script>";
	die;
}

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HorarioEstudianteBuscar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="horario-estudiante-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_asignaturas_x_niveles_sedes') ?>

    <?= $form->field($model, 'id_perfiles_x_personas_docentes') ?>

    <?= $form->field($model, 'id_aulas_x_sedes') ?>

    <?= $form->field($model, 'fecha_ingreso') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'id_paralelo_sede') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
