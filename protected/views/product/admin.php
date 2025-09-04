<?php
/* @var $this ProductController */
/* @var $model Product */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Product', 'url'=>array('index')),
	array('label'=>'Create Product', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#product-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h3>Manage Products</h3>


<?php 
// echo CHtml::link('Advanced Search','#',array('class'=>'search-button'));
 ?>
<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('error'); ?>
    </div>
<?php endif; ?>


<div class="csv-upload-form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'csv-upload-form',
        'action' => Yii::app()->createUrl('product/bulkUpload'),
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )); ?>

    <div class="form-group">
        <div class="row">
            <!-- File input -->
            <div class="col-md-6">
                <?php echo $form->labelEx($model, 'csvFile', array('class' => 'control-label')); ?>
                <?php echo $form->fileField($model, 'csvFile', array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'csvFile', array('class' => 'text-danger')); ?>
            </div>

            <!-- Button to align with the input field -->
            <div class="col-md-6">
                <div style="margin-top: 25px;">
                    <?php echo CHtml::submitButton('Upload CSV', array(
                        'class' => 'btn btn-primary'
                    )); ?>
                </div>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>




<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'product-grid',
	//  'dataProvider' =>  Product::model()->lowStock()->search(),
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'rowCssClassExpression' => '$data->lowStock()',
	// 'rowCssClassExpression' => '($data->quantity < 5) ? "low-stock" : ""',
	'columns'=>array(
		'id',
		array(
			 'name' => 'name',  // ✅ this will allow sorting by product name
        'value' => '$data->name',
        'sortable' => true, // optional, true by default
		),
		array(
			'name' => 'category_id',
			'value' => '$data->category ? $data->category->name : "N/A"',
			'filter' => CHtml::activeDropDownList(
				$model,
				'category_id',
				CHtml::listData(Category::model()->findAll(), 'id', 'name'),
				array('empty' => '-- Select Category --'),
			),
			'sortable' => true,
		),
		'quantity',
		'unit_price',
		// 'created_at',
		/*
		'updated_at',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
