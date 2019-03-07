# customapi

# Data
```store_name
store_address:string
store_geolocation:string
store_owner_name:string
store_city:string
store_country:string
store_email:string
order_id:number
order_amount
order_items = [
	product_name:string
	product_quantity:number
	product_brand:string
]
customer_name:string
customer_gender:boolean
customer_email:string
```
# Generate Fake Data
```
php bin/magento fakedata:generate --filename='filename.json' --records = 5000000
```
# Import Fake Data to Elastic Search
```
php bin/magento fakedata:import --user=admin --password=**** --filename='filename.json'
```
