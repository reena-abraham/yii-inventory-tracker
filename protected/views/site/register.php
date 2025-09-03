<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6"> <!-- Use col-md-4 for 4 columns width -->
            <h2>Register</h2>
            <p>Please fill out the following form with your registration details:</p>

            <div class="form">
                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'register-form',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                )); ?>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'name', array('class' => 'control-label')); ?>
                    <?php echo $form->textField($model, 'name', array('class' => 'form-control', 'autocomplete' => 'off',)); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'username', array('class' => 'control-label')); ?>
                    <?php echo $form->textField($model, 'username', array('class' => 'form-control', 'autocomplete' => 'off',)); ?>
                    <?php echo $form->error($model, 'username'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'email', array('class' => 'control-label')); ?>
                    <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'autocomplete' => 'off',)); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'password', array('class' => 'control-label')); ?>
                    <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'autocomplete' => 'off',)); ?>
                    <?php echo $form->error($model, 'password'); ?>
                </div>

                <div class="form-group">
                    <?php echo CHtml::submitButton('Register', array('class' => 'btn btn-primary btn-sm')); ?>
                </div>

                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>
