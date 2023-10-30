In this cron job we are fetching product attribute and check if product attribute is empty then import size and color attribute

 SQL Query for fetch product that assigned with specific attribute 

Sol) 

SELECT ea.attribute_code, pev.value
FROM `catalog_product_entity_varchar` as pev
JOIN `eav_attribute` as ea
ON pev.attribute_id = ea.attribute_id
WHERE pev.row_id = 23645
AND ea.attribute_code = 'swatch_data';
