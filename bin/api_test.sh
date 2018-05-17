#!/bin/bash

TOKEN=$(curl -s -X POST \
	-H "Content-Type: application/json" \
	-H "Accept: application/json" \
	-d '{"username": "demo", "password": "demo1234!"}' \
	http://krillo22.caupo.se/rest/V1/integration/admin/token)


# Get rid of quotes
TOKEN=$(echo $TOKEN | sed -e "s/^\"//" -e "s/\"$//")
# echo $TOKEN
# exit 0

# Add product
# RESULT=$(curl -s -X POST \
# 	-H "Authorization: Bearer $TOKEN" \
# 	-H "Content-Type: application/json" \
# 	-H "Accept: application/json" \
# 	-d '
# 	{
# 		"product": {
# 			"sku": "MR-Spex-76",
# 			"name": "Mr Spex 76",
# 			"price": 123,
# 			"type_id": "simple",
# 			"extension_attributes": {
# 				"category_links": [{
# 					"position": 1,
# 					"category_id": "3"
# 				}],
# 				"stock_item": {
# 					"qty": "10",
# 					"is_in_stock": true
# 				}
# 			}
# 		}
# 	}' \
#  	http://krillo22.caupo.se/rest/default/V1/products)

List all products
RESULT=$(curl -s -G -X GET \
	-H "Authorization: Bearer $TOKEN" \
	-H "Content-Type: application/json" \
	-H "Accept: application/json" \
	-d searchCriteria="*" \
	http://krillo22.caupo.se/rest/default/V1/products)

# Product types
# RESULT=$(curl -s -G -X GET \
# 	-H "Authorization: Bearer $TOKEN" \
# 	-H "Content-Type: application/json" \
# 	-H "Accept: application/json" \
# 	-d searchCriteria="*" \
# 	http://krillo22.caupo.se/rest/default/V1/categories)

echo $RESULT
echo
exit 0
