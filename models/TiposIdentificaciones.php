<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipos_identificaciones".
 *
 * @property string $id
 * @property string $descripcion
 * @property string $estado
 *
 * @property Personas[] $personas
 * @property Estados $estado0
 */
class TiposIdentificaciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_identificaciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['estado'], 'default', 'value' => null],
            [['estado'], 'integer'],
            [['descripcion'], 'string', 'max' => 60],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => Estados::className(), 'targetAttribute' => ['estado' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Personas::className(), ['id_tipos_identificaciones' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(Estados::className(), ['id' => 'estado']);
    }
}
