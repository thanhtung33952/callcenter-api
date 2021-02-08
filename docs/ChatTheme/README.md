### API public

**API_ROOT:** http://133.242.244.84/jibannet-callcenter-api

## Danh sách các API liên quan tới ChatTheme
###### 1. [Lấy toàn bộ ChatTheme](#1-lấy-toàn-bộ-chattheme-1)
###### 2. [Lấy thông tin ChatTheme từ id](#2-lấy-thông-tin-chattheme-từ-id-1)
###### 3. [Thêm 1 ChatTheme mới](#3. Thêm 1 ChatTheme mới)
###### 4. [Cập nhật ChatTheme theo id](#4. Cập nhật ChatTheme theo id)
###### 5. [Xóa ChatTheme theo id](#5. Xóa ChatTheme theo id)

**********************************

## Danh sách các API liên quan tới ChatTheme
## 1. Lấy toàn bộ ChatTheme
* **URL:** [{API_ROOT}/chatthemes](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* Truyền param "user_id" (không bắt buộc) để xác định được những chủ đề nào đã tồn tại trước đó (cột exist ở dữ liệu trả về)
* Nếu muốn **giới hạn số lượng record trả về** và **lấy bắt đầu từ vị trí nào** thì truyền thêm 2 param

  - **limit**: số lượng record trả về
  - **offset**: bắt đầu từ record thứ mấy
##### Ví dụ: 
		URL: [{API_ROOT}/chatthemes?user_id={user_id}&limit={limit}&offset={offset}

### Dữ liệu trả về:
-Cột exist: giá trị = 1 (đã tồn tại), giá trị = 0 (chưa tồn tại)
  ```
	[
	    {
			"id": int,
			"name": string,
			"exist": int
	    {
			"id": int,
			"name": string,
			"exist": int
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"id": "1",
			"name": "地盤安心住宅の申込",
			"exist": "0"
	    },
	    {
			"id": "2",
			"name": "地盤安心住宅の進捗",
			"exist": "1"
	    },
	    ...
	]
  ```

## <a name="2"></a>2. Lấy thông tin ChatTheme từ id
* **URL:** [{API_ROOT}/chattheme/{id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {id} là mã của ChatTheme id cần truyền vào

### Dữ liệu trả về:
```
	{
		"id": int,
		"name": string
	}
```

##### Ví dụ: {API_ROOT}/chattheme/2
Dữ liệu trả về:
```
	{
		"id": "1",
		"name": "地盤安心住宅の申込"
	}
```

## <a name="3"></a>3. Thêm 1 ChatTheme mới
* **URL:** [{API_ROOT}/chattheme](#)
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
		"name": "地盤安心住宅の申込"
	}
```

## <a name="4"></a>4. Cập nhật ChatTheme theo id
* **URL:** [{API_ROOT}/chattheme/{id}](#)
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
		"id": int,
		"name": string
	}
```

##### Ví dụ: 
```
	{
		"id": "6",
		"name": "地盤安心住宅の申込"
	}
```

## <a name="5"></a>5. Xóa ChatTheme theo id
* **URL:** [{API_ROOT}/chattheme/{id}](#)
* **Method:** DELETE
* **Content Type:** text/plain
* **Reponse Type:** text/json

### Tham số:
Truyền vào id của ChatTheme cần xóa


##### Ví dụ: 
		URL: [{API_ROOT}/chattheme/123

### Dữ liệu trả về:
- Xóa thành công: **Status code**= 200
- Xóa không thành công: **Status code** = 500
