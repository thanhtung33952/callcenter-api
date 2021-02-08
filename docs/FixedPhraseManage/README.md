### API public

**API_ROOT:** https://jiban.earth/jibannet-callcenter-api

## Danh sách các API liên quan tới Fixed Phrase
###### 1. [Lấy thông tin FixedPhrase từ staff id](#1-lấy-thông-tin-fixedphrase-từ-staff-id-1)
###### 2. [Thêm 1 FixedPhrase mới](#2-thêm-1-fixedphrase-mới-1)
###### 3. [Cập nhật FixedPhrase theo id](#3-cập-nhật-fixedphrase-theo-id-1)
###### 4. [Xóa FixedPhrase theo id](#4-xóa-fixedphrase-theo-id-1)

**********************************

## Danh sách các API liên quan tới Fixed Phrase


## <a name="1"></a>1. Lấy thông tin FixedPhrase từ staff id
* **URL:** [{API_ROOT}/fixedphrases/{id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {id} là mã của admin (staff_id) cần truyền vào

### Dữ liệu trả về:
    
  ```
	[
	    {
			"id": int,
			"staff_id": int,
			"title": string,
			"content": string,
        		"created": datetime
	    },
	    {
			"id": int,
			"staff_id": int,
			"title": string,
			"content": string,
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
			"staff_id": "12",
			"title": "Hello",
			"content": "Hello, Can I help you???",
			"created": "2020-02-25 18:05:18"
	    },
	    {
			"id": "2",
			"staff_id": "12",
			"title": "Hi",
			"content": "Good",
			"created": "2020-02-26 18:05:18"
	    },
	    ...
	]
  ```

## <a name="2"></a>2. Thêm 1 FixedPhrase mới
* **URL:** [{API_ROOT}/fixedphrase](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"staff_id": int,
		"title": string,
		"content": string
	}
```
staff_id là mã của admin

### Dữ liệu trả về:
```
	{
	    	"id": int,
		"staff_id": int,
		"title": string,
		"content": string
	}
```

##### Ví dụ: 
```
	{
		"staff_id": "12",
		"title": "Hello",
		"content": "Hello, Can I help you?",
		"id": "1"
	}
```

## <a name="3"></a>3. Cập nhật FixedPhrase theo id
* **URL:** [{API_ROOT}/fixedphrase/{id}](#)
* **Method:** PUT
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
Truyền vào id của fixedphrase cần chỉnh sửa vô url
```
	{
		"staff_id": int,
		"title": string,
		"content": string
	}
```

### Dữ liệu trả về:
```
	{
		"staff_id": int,
		"title": string,
		"content": string
	}
```

##### Ví dụ: 
```
	{
		"staff_id": "12",
		"title": "Hello",
		"content": "Hello, Can I help you???"
	}
```

## <a name="4"></a>4. Xóa FixedPhrase theo id
* **URL:** [{API_ROOT}/fixedphrase/{id}](#)
* **Method:** DELETE
* **Content Type:** text/plain
* **Reponse Type:** text/json

### Tham số:
Truyền vào id của fixedphrase cần xóa


##### Ví dụ: 
		URL: [{API_ROOT}/fixedphrase/123

### Dữ liệu trả về:
- Xóa thành công: **Status code**= 200
- Xóa không thành công: **Status code** = 500
