<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">


	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
	<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<style>
		.low-stock {
    		background-color: #f06e6e;
			color: black;
		}
     /* Container for the grid layout */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Adjust to your preferred grid layout */
    gap: 20px; /* Space between grid items */
    margin-top: 20px;
}

/* Styling each product card */
.product-card {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Image styling */
.product-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 15px;
    border-radius: 10px;
}

/* Product title styling */
.product-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

/* Category text styling */
.product-category {
    font-size: 14px;
    color: #777;
}

/* Price styling */
.product-price {
    font-size: 16px;
    color: #2d72d9; /* Blue color for price */
    font-weight: bold;
}

/* Responsive Layout for smaller screens */
@media (max-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}


		</style>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),

				array('label' => 'Users', 'url' => array('/users/index'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->user->getState('role') == 1),
				array('label' => 'Categories', 'url' => array('/category/index'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->user->getState('role') == 1),
				array('label' => 'Products', 'url' => array('/product/index'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->user->getState('role') == 1),
				
				array('label' => 'Products', 'url' => array('/product/list'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->user->getState('role') == 2),

				array('label' => 'Register', 'url' => array('/site/register'), 'visible' => Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

<!-- <script src="https://cdn.tailwindcss.com"></script> -->

	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script> -->

</body>
</html>
