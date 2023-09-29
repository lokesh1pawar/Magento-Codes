app/code/Plumtree/CatalogExtended/view/frontend/layout/catalog_category_view.xml

<referenceContainer name="content">
           <block class="Plumtree\CatalogExtended\Block\Product\ProductList\ThreeUnitsSold" name="three.units.sold" as="three_units_sold" />
		</referenceContainer>

----------------------------------------

app/design/frontend/Plumtree/everlast/Magento_Catalog/templates/product/list.phtml
             
   <?php //Selling Fast Label 
      ?>
   <?php $blockThreeUnitsSold = $block->getLayout()->getBlock('three.units.sold');
   $products = $blockThreeUnitsSold->getTopSellingProducts();
     if (in_array($_product->getId(), $products)) : ?>
     <div id="fast_selling_tag" class="fast-selling">
         <?= __("Selling Fast") ?>
                            </div>
         <?php endif; ?>

----------------------------------------

app/design/frontend/Plumtree/everlast/Magento_Catalog/templates/product/view/addtocart.phtml
             
 <!-- Selling Fast Label  -->
    <?php $blockThreeUnitsSold = $block->getLayout()->getBlock('three.units.sold');
    $products = $blockThreeUnitsSold->getTopSellingProducts();
    if (in_array($_product->getId(), $products)) : ?>
        <div id="fast_selling_tag" class="fast-selling">
            <?= __("Selling Fast") ?>
        </div>
    <?php endif; ?>
