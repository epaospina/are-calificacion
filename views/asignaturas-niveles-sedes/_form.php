<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sedes;
use	yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\AsignaturasNivelesSedes */
/* @var $form yii\widgets\ActiveForm */

?>


<script>


function parametroUrl(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

window.onload = llenarListasActualizar();

function llenarListasActualizar() 
{
	var url = window.location.href; 
	if (url.indexOf('update')!=-1) 
	{
		setTimeout(function(){ llenarListas(); }, 2000);	
	}
}

function llenarListas()
{		
	$.post("../views/asignaturas-niveles-sedes/llenarListas.php",
				{
					idSede:$("#sedes-descripcion").val(),
				},
				function(data){
					if(data.error == 1)
					{
						alert('La sede no tiene niveles o asignaturas');	
						$("#asignaturasnivelessedes-id_sedes_niveles").html(data.niveles);
						$("#asignaturasnivelessedes-id_asignaturas").html(data.asignaturas);						
					}
					else
					{
						$("#asignaturasnivelessedes-id_sedes_niveles").html(data.niveles);
						$("#asignaturasnivelessedes-id_asignaturas").html(data.asignaturas);
						
					}
				
				},
				"json"
				
		  );
	
}


</script>

<div class="asignaturas-niveles-sedes-form">

    <?php $form = ActiveForm::begin(); ?>
    
	<?php 
		$model1 = new Sedes();
		$model1->id=$idSedes;
		$sedes = Sedes::find()->orderby('descripcion')->all();
		$sedes = ArrayHelper::map($sedes,'id','descripcion');		
		echo $form->field($model1, 'descripcion')->dropDownList( $sedes, ['prompt'=>'Seleccione...','onchange'=>'llenarListas()','options' => [$model1['id'] => ['selected' => 'selected']]] )->label('Sedes');
		
    ?>
		        

	<?= $form->field($model, 'id_sedes_niveles')->dropDownList(['prompt'=>'Seleccione...'])->label('Niveles') ?>
	
    <?= $form->field($model, 'id_asignaturas')->dropDownList(['prompt'=>'Seleccione...']) ?>

    <?= $form->field($model, 'intensidad')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>