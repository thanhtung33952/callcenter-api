### API public

**API_ROOT:** http://133.242.244.84/jibannet-callcenter-api

## Danh sách các API liên quan tới Company
###### 1. [Lấy toàn bộ Company](#1. Lấy toàn bộ Company)
###### 2. [Lấy thông tin Company từ id](#2. Lấy thông tin Company từ id)
###### 3. [Thêm 1 Company mới](#3. Thêm 1 Company mới)
###### 4. [Cập nhật Company theo id](#4. Cập nhật Company theo id)
###### 5. [Xóa Company theo id](#5. Xóa Company theo id)

**********************************

## Danh sách các API liên quan tới Company
## 1. Lấy toàn bộ Company
* **URL:** [{API_ROOT}/companies](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* Nếu muốn **giới hạn số lượng record trả về** và **lấy bắt đầu từ vị trí nào** thì truyền thêm 2 param

  - **limit**: số lượng record trả về
  - **offset**: bắt đầu từ record thứ mấy
##### Ví dụ: 
		URL: [{API_ROOT}/companies?limit={limit}&offset={offset}

### Dữ liệu trả về:
    
  ```
	[
	    {
			"id": int,
			"name": string
	    {
			"id": int,
			"name": string
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"id": "1",
			"name": "Company 1"
	    },
	    {
			"id": "2",
			"name": "Company 2"
	    },
	    ...
	]
  ```

## <a name="2"></a>2. Lấy thông tin Company từ id
* **URL:** [{API_ROOT}/company/{id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {id} là mã của Company id cần truyền vào

### Dữ liệu trả về:
```
	{
		"id": int,
		"name": string
	}
```

##### Ví dụ: {API_ROOT}/company/2
Dữ liệu trả về:
```
	{
		"id": "1",
		"name": "Company 2"
	}
```

## <a name="3"></a>3. Thêm 1 Company mới
* **URL:** [{API_ROOT}/company](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"name": string
	}
```

### Dữ liệu trả về:
```
	{
	    "id": int,
		"name": string
	}
```

##### Ví dụ: 
```
	{
		"id": "1",
		"name": "Company 2"
	}
```

## <a name="4"></a>4. Cập nhật Company theo id
* **URL:** [{API_ROOT}/company/{id}](#)
* **Method:** PUT
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"name": string
	}
```

### Dữ liệu trả về:
```
	{
		"name": string
	}
```

##### Ví dụ: 
```
	{
		"name": "Company 2"
	}
```

## <a name="5"></a>5. Xóa Company theo id
* **URL:** [{API_ROOT}/company/{id}](#)
* **Method:** DELETE
* **Content Type:** text/plain
* **Reponse Type:** text/json

### Tham số:
Truyền vào id của Company cần xóa


##### Ví dụ: 
		URL: [{API_ROOT}/company/123

### Dữ liệu trả về:
- Xóa thành công: **Status code**= 200
- Xóa không thành công: **Status code** = 500
