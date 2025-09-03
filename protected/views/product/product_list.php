<div class="container mt-5">
    <h2>Product List</h2>

    <!-- Custom Grid for Products -->
    <div class="product-grid">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <!-- Product Image -->
                    <img class="product-image" src="<?php echo Yii::app()->request->baseUrl?>/images/default.png" alt="Product Image">
                    
                    <div class="product-details">
                        <h5 class="product-title"><?php echo CHtml::encode($product->name); ?></h5>
                        <p class="product-category">
                            Category: <?php echo CHtml::encode($product->category->name); ?>
                        </p>
                        <p class="product-price">
                            Price: AED <?php echo CHtml::encode($product->unit_price); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</div>
