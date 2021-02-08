### API public

**API_ROOT:** http://133.242.244.84/jibannet-callcenter-api

## Danh sách các API liên quan tới CentersStaff
###### 1. [Lấy toàn bộ CentersStaff](#1. Lấy toàn bộ CentersStaff)
###### 2. [Lấy thông tin CentersStaff từ id](#2. Lấy thông tin CentersStaff từ id)
###### 3. [Thêm 1 CentersStaff mới](#3. Thêm 1 CentersStaff mới)
###### 4. [Cập nhật CentersStaff theo id](#4. Cập nhật CentersStaff theo id)
###### 5. [Xóa CentersStaff theo id](#5. Xóa CentersStaff theo id)

**********************************

## Danh sách các API liên quan tới CentersStaff
## 1. Lấy toàn bộ CentersStaff
* **URL:** [{API_ROOT}/centersstaffs](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* Nếu muốn **giới hạn số lượng record trả về** và **lấy bắt đầu từ vị trí nào** thì truyền thêm 2 param

  - **limit**: số lượng record trả về
  - **offset**: bắt đầu từ record thứ mấy
##### Ví dụ: 
		URL: [{API_ROOT}/centersstaffs?limit={limit}&offset={offset}

### Dữ liệu trả về:
    
  ```
	[
	    {
			"id": int,
			"email": string,
			"family_name": string,
			"given_name": string
	    },
	    {
			"id": int,
			"email": string,
			"family_name": string,
			"given_name": string
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"id": "1",
			"email": "user1@gmail.com",
			"family_name": "Staff 1",
			"given_name": ""
	    },
	    {
			"id": "2",
			"email": "user2@gmail.com",
			"family_name": "Staff 2",
			"given_name": ""
	    },
	    ...
	]
  ```

## <a name="2"></a>2. Lấy thông tin CentersStaff từ id
* **URL:** [{API_ROOT}/centersstaff/{id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {id} là mã của CentersStaff id cần truyền vào

### Dữ liệu trả về:
```
	{
		"id": int,
		"email": string,
		"family_name": string,
		"given_name": string
	}
```

##### Ví dụ: {API_ROOT}/centersstaff/2
Dữ liệu trả về:
```
	{
		"id": "2",
		"email": "user2@gmail.com",
		"family_name": "Staff 2",
		"given_name": ""
	}
```

## <a name="3"></a>3. Thêm 1 CentersStaff mới
* **URL:** [{API_ROOT}/centersstaff](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"email": string,
		"family_name": string,
		"given_name": string
	}
```

### Dữ liệu trả về:
```
	{
	    "id": int,
		"email": string,
		"family_name": string,
		"given_name": string
	}
```

##### Ví dụ: 
```
	{
		"id": "2",
		"email": "user2@gmail.com",
		"family_name": "Staff 2",
		"given_name": ""
	}
```

## <a name="4"></a>4. Cập nhật CentersStaff theo id
* **URL:** [{API_ROOT}/centersstaff/{id}](#)
* **Method:** PUT
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"email": string,
		"family_name": string,
		"given_name": string
	}
```

### Dữ liệu trả về:
```
	{
		"email": string,
		"family_name": string,
		"given_name": string
	}
```

##### Ví dụ: 
```
	{
		"id": "2",
		"email": "user2@gmail.com",
		"family_name": "Staff 2",
		"given_name": ""
	}
```

## <a name="5"></a>5. Xóa CentersStaff theo id
* **URL:** [{API_ROOT}/centersstaff/{id}](#)
* **Method:** DELETE
* **Content Type:** text/plain
* **Reponse Type:** text/json

### Tham số:
Truyền vào id của CentersStaff cần xóa


##### Ví dụ: 
		URL: [{API_ROOT}/centersstaff/123

### Dữ liệu trả về:
- Xóa thành công: **Status code**= 200
- Xóa không thành công: **Status code** = 500
