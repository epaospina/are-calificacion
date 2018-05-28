<?php
/**********
Versión: 001
Fecha: 27-03-2018
Desarrollador: Oscar David Lopez
Descripción: CRUD de Representantes Legales (Estudiantes)
---------------------------------------
Modificaciones:
Fecha: 27-04-2018
Persona encargada: Oscar David Lopez
Cambios realizados: - Horario del Docente con datatables
---------------------------------------
**********/
namespace app\controllers;

use Yii;
use app\models\HorarioDocente;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;

/**
 * HorarioDocenteController implements the CRUD actions for HorarioDocente model.
 */
class HorarioDocenteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	
	public function actionListarInstituciones( $idInstitucion = 0, $idSedes = 0 )
    {
        return $this->render('listarInstituciones',[
			'idSedes' 		=> $idSedes,
			'idInstitucion' => $idInstitucion,
		] );
    }


    /**
     * Lists all HorarioDocente models.
     * @return mixed
     */
    public function actionIndex($idInstitucion = 0, $idSedes = 0, $idDocente = 0)
    {
		
		if( $idInstitucion != 0 && $idSedes != 0 )
		{
		
		$data = [[],[],];		
						
			
		$dataProvider = new ArrayDataProvider([
			'allModels' => $data,
		]);
				
		
		//modelo para el form
		$model = new HorarioDocente();
		
		//variable con la conexion a la base de datos  pe.id=10 es el perfil docente
		$connection = Yii::$app->getDb();
		//llenar los docente
		$command = $connection->createCommand("
			select d.id_perfiles_x_personas as id, concat(p.nombres,' ',p.apellidos) as nombres
			from personas as p, perfiles_x_personas as pp, docentes as d, perfiles as pe,perfiles_x_personas_institucion as ppi
			where p.id= pp.id_personas
			and p.estado=1
			and pp.id_perfiles=pe.id
			and pe.id=10
			and pe.estado=1
			and pp.id= d.id_perfiles_x_personas
			and ppi.id_perfiles_x_persona = pp.id
			and ppi.id_institucion = $idInstitucion");
		$result = $command->queryAll();
		//se formatea para que lo reconozca el select
		$docentes=array();
		foreach($result as $key)
		{
			$docentes[$key['id']]=$key['nombres'];
		}
		
		
		if ($idDocente != 0)
		{
			//que materias se dan y en que dias en la sede actual
		$command = $connection->createCommand("
		select da.id , d.descripcion as dias, b.descripcion as bloques, a.descripcion as asignatura,
		pa.descripcion as grupo, au.descripcion as aula
			from distribuciones_academicas as da, asignaturas_x_niveles_sedes as ans, sedes_niveles as sn, dias as d, 
			bloques as b , distribuciones_x_bloques_x_dias as dbd, sedes_x_bloques as sb, asignaturas as a, personas as p,perfiles_x_personas as pp,
			paralelos as pa, aulas as au
			where da.id_asignaturas_x_niveles_sedes = ans.id
			AND sn.id = ans.id_sedes_niveles
			AND sn.id_sedes = $idSedes
			AND da.estado = 1
			AND dbd.id_distribuciones_academicas = da.id
			AND dbd.id_dias = d.id
			AND dbd.id_bloques_sedes = sb.id
			AND sb.id_bloques = b.id
			AND ans.id_asignaturas= a.id
			AND da.id_perfiles_x_personas_docentes = pp.id
			and pp.id_personas=p.id
			and da.id_paralelo_sede= pa.id
			and p.estado = 1
			and pa.estado= 1
			and da.estado= 1
			and da.id_perfiles_x_personas_docentes = $idDocente
			and da.id_aulas_x_sedes = au.id");
			$result = $command->queryAll();
		
			$command = $connection->createCommand("SELECT id, descripcion
			FROM dias
			where estado  =1
			order by id");
			$dias = $command->queryAll();
			
			
			$command = $connection->createCommand("
			SELECT b.id, b.descripcion
			FROM bloques as b, sedes_x_bloques as sb 
			where b.estado  =1
			and sb.id_sedes =$idSedes
			and sb.id_bloques = b.id
			order by id asc");
			$bloques = $command->queryAll();
			
			$arrayHorario=array();
			//se crea un array con los bloque de la sede VS los dias de la semana con el valor no asignado
			foreach ($dias as $dia)
			{
				foreach ($bloques as $bloque)
				{
					//$bloque['descripcion'] nombre del bloque 
					//$dia['descripcion'] dia de la semana
					$arrayHorario[$bloque['descripcion']][$dia['descripcion']]="-"."</insertar>";
				}
				
				
			}			
			
			//en la ubicacion bloque - dia se pone el nombrede la asignatura - group - aula que da ese docente $idDocente
			foreach($result as $r)
			{
				
				$arrayHorario[$r['bloques']][$r['dias']]=$r['asignatura']." |".$r['grupo']."|".$r['aula']."</actualizar=".$r['id']."";
			}
			
			
			//se construye el formato json para llenar el dataTable
			$data='[';
			$data.='{"bloques":" BLOQUE ","LUNES":"LUNES","MARTES":"MARTES","MIERCOLES":"MIERCOLES","JUEVES":"JUEVES","VIERNES":"VIERNES","SABADO":"SABADO","DOMINGO":"DOMINGO"},';
			foreach($arrayHorario as $arrayHorarioJson=>$valor) 
			{
				$data.='{"bloques":"'.$arrayHorarioJson.'",';
				foreach($valor as $v=>$value)
				{
					// print_r($v);
					$arraydata[]='"'.$v.'":"'.$value.'"';
				}
				$data.=implode(",",$arraydata);
				unset($arraydata);
				$data.='},';
			}
			$data = substr($data, 0, -1);
			$data.=']';
			echo $data;
			die;
		}
		
		
		
        return $this->render('index', [
            // 'dataProvider' => $dataProvider,
			'model'=>$model,
			'docentes'=>$docentes,
			'idSedes' 	=> $idSedes,
			'idInstitucion' => $idInstitucion,
			'dataProvider'=>$dataProvider,
			]);
		}
		else
		{
			// Si el id de institucion o de sedes es 0 se llama a la vista listarInstituciones
			 return $this->render('listarInstituciones',[
				'idSedes' 		=> $idSedes,
				'idInstitucion' => $idInstitucion,
			] );
		}

    }

    /**
     * Displays a single HorarioDocente model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HorarioDocente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HorarioDocente();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HorarioDocente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HorarioDocente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HorarioDocente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return HorarioDocente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HorarioDocente::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
