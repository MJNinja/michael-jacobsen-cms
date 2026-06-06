<div class="module-stats-label">
    Total Main Categories:
    <b><?php echo $productManager->getTotalCategories();?></b>
</div>
<div class="module-stats-label">
    Total Sub Categories:
    <b><?php echo $productManager->getTotalSubCategories();?></b>
</div>
<div class="module-stats-label">
    Total Products:
    <b><?php echo $productManager->getTotalProducts();?></b>
</div>
<div class="module-stats-label">
    Empty Products:
    <b><?php echo $productManager->getEmptyProducts();?></b>
</div>
<div class="module-stats-label">
    Category Image Sizes:<br />
    Width: <strong><?php echo $categoryWidth; ?>px</strong><br />
    Height: <strong><?php echo $categoryHeight; ?>px</strong>
</div>
<div class="module-stats-label">
    Product Image Sizes:<br />
    Width: <strong><?php echo $productWidth; ?>px</strong><br />
    Height: <strong><?php echo $productHeight; ?>px</strong>
</div>
<div class="module-stats-label">
    Paragraph Image Sizes:<br />
    Width: <strong><?php echo $paragraphWidth; ?>px</strong><br />
    Height: <strong><?php echo $paragraphHeight; ?>px</strong>
</div>
