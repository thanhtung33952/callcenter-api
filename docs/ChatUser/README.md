### API public

**API_ROOT:** http://133.242.244.84/jibannet-callcenter-api

## Danh sách các API liên quan tới ChatUser
###### 1. [Lấy toàn bộ ChatUser](#1. Lấy toàn bộ ChatUser)
###### 2. [Lấy thông tin ChatUser từ id](#2. Lấy thông tin ChatUser từ id)
###### 3. [Thêm 1 ChatUser mới](#3. Thêm 1 ChatUser mới)
###### 4. [Cập nhật ChatUser theo id](#4. Cập nhật ChatUser theo id)
###### 5. [Xóa ChatUser theo id](#5. Xóa ChatUser theo id)
###### 6. [Kiểm tra đăng nhập ChatUser](#6. Kiểm tra đăng nhập ChatUser)

**********************************

## Danh sách các API liên quan tới ChatUser
## 1. Lấy toàn bộ ChatUser
* **URL:** [{API_ROOT}/chatusers](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* Nếu muốn **giới hạn số lượng record trả về** và **lấy bắt đầu từ vị trí nào** thì truyền thêm 2 param

  - **limit**: số lượng record trả về
  - **offset**: bắt đầu từ record thứ mấy
##### Ví dụ: 
		URL: [{API_ROOT}/chatusers?limit={limit}&offset={offset}

### Dữ liệu trả về:
    
  ```
	[
	    {
			"id": int,
			"email": string,
			"family_name": string,
			"given_name": string,
			"company_id": int,
			"phone": string,
			"created": datetime
	    },
	    {
			"id": int,
			"email": string,
			"family_name": string,
			"given_name": string,
			"company_id": int,
			"phone": string,
			"created": datetime
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"id": "1",
			"email": "userchat1@gmail.com",
			"family_name": "User Chat 1",
			"given_name": "User Chat 1",
			"company_id": "1",
			"phone": "123456789",
			"created": "2019-11-20 18:43:19"
	    },
	    {
			"id": "2",
			"email": "userchat2@gmail.com",
			"family_name": "User Chat 2",
			"given_name": "User Chat 2",
			"company_id": "2",
			"phone": "123456789",
			"created": "2019-11-20 18:43:19"
	    },
	    ...
	]
  ```

## <a name="2"></a>2. Lấy thông tin ChatUser từ id
* **URL:** [{API_ROOT}/chatuser/{id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {id} là mã của ChatUser id cần truyền vào

### Dữ liệu trả về:
```
	{
		"id": int,
		"email": string,
		"family_name": string,
		"given_name": string,
		"company_id": int,
		"company_name": string,
		"phone": string,
		"created": datetime
	}
```

##### Ví dụ: {API_ROOT}/chatuser/2
Dữ liệu trả về:
```
	{
		"id": "2",
		"email": "userchat2@gmail.com",
		"family_name": "User Chat 2",
		"given_name": "User Chat 2",
		"company_id": "2",
		"company_name": "Jibannet",
		"phone": "123456789",
		"created": "2019-11-20 18:43:19"
	}
```

## <a name="3"></a>3. Thêm 1 ChatUser mới
* **URL:** [{API_ROOT}/chatuser](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"email": string,
		"family_name": string,
		"password": string,
		"company_name": string,
		"phone": string
	}
```

### Dữ liệu trả về:
Trường hợp kiểm tra đã tồn tại trong hệ thống sẽ trả về id = -1 (trùng email + password trong hệ thống)
```
	{
	    "id": int,
		"email": string,
		"family_name": string,
		"password": string,
		"company_name": string,
		"phone": string
	}
```

##### Ví dụ: 
```
	{
		"id": "2",
		"email": "userchat2@gmail.com",
		"family_name": "User Chat 2",
		"password": "1234",
		"company_name": "Jibannet",
		"phone": "123456789"
	}
```

## <a name="4"></a>4. Cập nhật ChatUser theo id
* **URL:** [{API_ROOT}/chatuser/{id}](#)
* **Method:** PUT
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"email": string,
		"family_name": string,
		"password": string,
		"company_id": int,
		"phone": string
	}
```

### Dữ liệu trả về:
```
	{
		"email": string,
		"family_name": string,
		"password": string,
		"company_id": int,
		"phone": string
	}
```

##### Ví dụ: 
```
	{
		"email": "userchat2@gmail.com",
		"family_name": "User Chat 2",
		"password": "12345",
		"company_id": "2",
		"phone": "123456789"
	}
```

## <a name="5"></a>5. Xóa ChatUser theo id
* **URL:** [{API_ROOT}/chatuser/{id}](#)
* **Method:** DELETE
* **Content Type:** text/plain
* **Reponse Type:** text/json

### Tham số:
Truyền vào id của ChatUser cần xóa


##### Ví dụ: 
		URL: [{API_ROOT}/chatuser/123

### Dữ liệu trả về:
- Xóa thành công: **Status code**= 200
- Xóa không thành công: **Status code** = 500

## <a name="3"></a>6. Kiểm tra đăng nhập ChatUser
* **URL:** [{API_ROOT}/loginchatuser](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"email": string,
		"password": string
	}
```

### Dữ liệu trả về:
Trường hợp kiểm tra không tồn tại trong hệ thống sẽ trả về id = -1 
```
	{
	    	"id": int,
		"email": string,
		"family_name": string,
		"company_name": string,
		"phone": string,
		"created": datetime
	}
```

##### Ví dụ: 
```
	{
		"id": "2",
		"email": "userchat2@gmail.com",
		"family_name": "User Chat 2",
		"company_name": "Jibannet",
		"phone": "123456789",
		"created": "2020-02-21"
	}
```
