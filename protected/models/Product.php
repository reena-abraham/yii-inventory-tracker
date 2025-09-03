<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property integer $quantity
 * @property string $unit_price
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Category $category
 */
class Product extends CActiveRecord
{
	public $csvFile;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, category_id,quantity,unit_price', 'required'),
			array('category_id, quantity,unit_price', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 255),
			array('unit_price', 'length', 'max' => 10),
			array('created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, category_id, quantity, unit_price, created_at, updated_at', 'safe', 'on' => 'search'),


			array('csvFile', 'file', 'types' => 'csv', 'allowEmpty' => false, 'safe' => false),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'category_id' => 'Category',
			'quantity' => 'Quantity',
			'unit_price' => 'Unit Price',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('category_id', $this->category_id);
		$criteria->compare('quantity', $this->quantity);
		$criteria->compare('unit_price', $this->unit_price, true);
		$criteria->compare('created_at', $this->created_at, true);
		$criteria->compare('updated_at', $this->updated_at, true);


		

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 10, // Change as needed
			),
		));
	}

	public function beforeSave()
	{
		// Automatically set 'created_at' if the record is new
		if ($this->isNewRecord) {
			$this->created_at = new CDbExpression('CURRENT_TIMESTAMP');
		}
		// Update 'updated_at' timestamp on save
		$this->updated_at = new CDbExpression('CURRENT_TIMESTAMP');

		return parent::beforeSave();
	}
	public function lowStock()
	{
		// $this->getDbCriteria()->mergeWith(array(
		// 	'condition' => 'quantity < 5', 
		// ));
		// return $this;
		return ($this->quantity < 5) ? 'low-stock' : '';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
