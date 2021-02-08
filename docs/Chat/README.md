### API public

**API_ROOT:** http://133.242.244.84/jibannet-callcenter-api

## Danh sách các API liên quan tới Chat
###### 2. [Lấy thông tin Chat từ id](#2.-Lấy-thông-tin-Chat-từ-id)
###### 3. [Thêm 1 Chat mới](#3. Thêm 1 Chat mới)
###### 4. [Cập nhật Chat theo id](#4. Cập nhật Chat theo id)
###### 5. [Xóa Chat theo id](#5. Xóa Chat theo id)
###### 6. [Lấy thông tin Chat từ faq id và user id](#6. Lấy thông tin Chat từ faq id và user id)
###### 7. [Cập nhật xem tin nhắn Chat theo Chat Id](#7. Cập nhật xem tin nhắn Chat theo Chat Id)

**********************************

## Danh sách các API liên quan tới Chat


## <a name="2"></a>2. Lấy thông tin Chat từ id
* **URL:** [{API_ROOT}/chat/{id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {id} là mã của Chat id cần truyền vào

### Dữ liệu trả về:
    
  ```
	[
	    {
			"id": int,
			"chat_id": int,
			"writer_name": string,
			"receiver_name": string,
			"writer_id": int,
			"time": datetime,
			"text": string,
			"type": int
	    },
	    {
			"id": int,
			"chat_id": int,
			"writer_name": string,
			"receiver_name": string,
			"writer_id": int,
			"time": datetime,
			"text": string,
			"type": int
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"id": "1",
			"chat_id": "1",
			"writer_name": "User 1",
			"receiver_name": "Staff 1",
			"writer_id": "1",
			"time": "2019-11-20 18:45:43",
			"text": "test",
			"type": "0"
	    },
	    {
			"id": "2",
			"chat_id": "1",
			"writer_name": "Staff 1",
			"receiver_name": "User 1",
			"writer_id": "1",
			"time": "2019-11-20 18:45:4",
			"text": "test1",
			"type": "0"
	    },
	    ...
	]
  ```

## <a name="3"></a>3. Thêm 1 Chat mới
* **URL:** [{API_ROOT}/chat](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"chat_id": int,
		"writer_id": int,
		"text": string,
		"type": int // không bắt buộc (0: send text, 1: notification)
	}
```
writer_id là mã xác nhận ai là người nhắn (1: user, 0: staff)
trường hợp type là thông báo (type = 1 ) -> ở tham số text truyền vào nội dung thông báo. vd: 通話終了 (kết thúc hội thoại)
### Dữ liệu trả về:
```
	{
	    	"id": int,
		"chat_id": int,
		"writer_id": int,
		"text": string,
		"type": int,
		"time": datetime
	}
```

##### Ví dụ: 
```
	{
		"id": "1",
		"chat_id": "1",
		"writer_id": "1",
		"text": "test",
		"type": "0",
		"time": "2020-03-02 17:52:03"
	}
```

## <a name="4"></a>4. Cập nhật Chat theo id
* **URL:** [{API_ROOT}/chat/{id}](#)
* **Method:** PUT
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"text": string
	}
```

### Dữ liệu trả về:
```
	{
		"text": string
	}
```

##### Ví dụ: 
```
	{
		"text": "test"
	}
```

## <a name="5"></a>5. Xóa Chat theo id
* **URL:** [{API_ROOT}/chat/{id}](#)
* **Method:** DELETE
* **Content Type:** text/plain
* **Reponse Type:** text/json

### Tham số:
Truyền vào id của Chat cần xóa


##### Ví dụ: 
		URL: [{API_ROOT}/chat/123

### Dữ liệu trả về:
- Xóa thành công: **Status code**= 200
- Xóa không thành công: **Status code** = 500


## <a name="6"></a>6. Lấy thông tin Chat từ faq id và user id
* **URL:** [{API_ROOT}/chatsbyfaq?user_id={user_id}&faq1_id={faq1_id}&faq2_id={faq2_id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {user_id} là mã của user chat cần truyền vào
* {faq1_id} {faq1_id} là mã của faq cần truyền vào

### Dữ liệu trả về:
    
  ```
	[
	    {
			"id": int,
			"chat_id": int,
			"writer_name": string,
			"receiver_name": string,
			"writer_id": int,
			"time": datetime,
			"text": string,
			"type": int
	    },
	    {
			"id": int,
			"chat_id": int,
			"writer_name": string,
			"receiver_name": string,
			"writer_id": int,
			"time": datetime,
			"text": string,
			"type": int
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"id": "1",
			"chat_id": "1",
			"writer_name": "User 1",
			"receiver_name": "Staff 1",
			"writer_id": "1",
			"time": "2019-11-20 18:45:43",
			"text": "test",
			"type": "0"
	    },
	    {
			"id": "2",
			"chat_id": "1",
			"writer_name": "Staff 1",
			"receiver_name": "User 1",
			"writer_id": "1",
			"time": "2019-11-20 18:45:4",
			"text": "test1",
			"type": "0"
	    },
	    ...
	]
  ```

## <a name="7"></a>7. Cập nhật xem tin nhắn Chat theo Chat Id
* **URL:** [{API_ROOT}/updateseenchat/{id}](#)
* **Method:** PUT
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
id chính là chat_id (id chat của cuộc hội thoại)


### Dữ liệu trả về:
- Cập nhật thành công: **Status code**= 200
- Cập nhật không thành công: **Status code** = 500
