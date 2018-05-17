#!/bin/bash

TOKEN=$(curl -s -X POST \
	-H "Content-Type: application/json" \
	-H "Accept: application/json" \
	-d '{"username": "demo", "password": "demo1234!"}' \
	http://krillo22.caupo.se/rest/V1/integration/admin/token)

# Get rid of quotes
echo $TOKEN | sed -e "s/^\"//" -e "s/\"$//"
exit 0
