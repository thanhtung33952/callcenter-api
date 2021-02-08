### API public

**API_ROOT:** https://jiban.earth/jibannet-callcenter-api

## Danh sách các API liên quan tới ChatManage
###### 1. [Lấy toàn bộ ChatManage](#1-lấy-toàn-bộ-chatmanage-1)
###### 2. [Lấy thông tin ChatManage từ id](#2-lấy-thông-tin-chatmanage-từ-id-1)
###### 3. [Thêm 1 ChatManage mới](#3-thêm-1-chatmanage-mới-1)
###### 4. [Cập nhật ChatManage theo id](#4-cập-nhật-chatmanage-theo-id-1)
###### 5. [Xóa ChatManage theo id](#5-xóa-chatmanage-theo-id-1)
###### 6. [Cập nhật thời gian kết thúc ChatManage theo id](#6-cập-nhật-thời-gian-kết-thúc-chatmanage-theo-id-1)
###### 7. [Thêm 1 Client Request mới](#7-thêm-1-client-request-mới-1)
###### 8. [Lấy danh sách ChatManage từ staff id](#8-lấy-danh-sách-chatmanage-từ-staff-id-1)
###### 9. [Lấy danh sách users chat đã từng chat với staff](#9-lấy-danh-sách-users-chat-đã-từng-chat-với-staff-1)

**********************************

## Danh sách các API liên quan tới ChatManage
## 1. Lấy toàn bộ ChatManage
* **URL:** [{API_ROOT}/chatmanages](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* Nếu muốn **giới hạn số lượng record trả về** và **lấy bắt đầu từ vị trí nào** thì truyền thêm 2 param

  - **limit**: số lượng record trả về
  - **offset**: bắt đầu từ record thứ mấy
  - **missedcall**: để lọc theo cuộc gọi nhỡ
##### Ví dụ: 
		URL: [{API_ROOT}/chatmanages?limit={limit}&offset={offset}

### Dữ liệu trả về:
    missed_call: 1: cuộc gọi nhỡ, 0: không phải cuộc gọi nhỡ
  ```
	[
	    {
			"id": "int",
			"user_id": "int",
			"family_name": "string",
			"missed_call": "int",
			"total_not_seen": "int",
			"faq1_title": "string",
			"faq2_title": "string",
			"faq3_title": "string",
			"start": "datetime"
	    },
	    {
			"id": "int",
			"user_id": "int",
			"family_name": "string",
			"missed_call": "int",
			"total_not_seen": "int",
			"faq1_title": "string",
			"faq2_title": "string",
			"faq3_title": "string",
			"start": "datetime"
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"id": "1",
			"user_id": "1",
			"family_name": "YAMADA",
			"missed_call": "0",
			"total_not_seen": "5",
			"faq1_title": "個人のお客様",
			"faq2_title": "地盤の調査をしたい",
			"faq3_title": null,
			"start": "2020-03-13 18:29:01"
	    },
	    {
			"id": "2",
			"user_id": "2",
			"family_name": "田中",
			"missed_call": "0",
			"total_not_seen": "1",
			"faq1_title": "個人のお客様",
			"faq2_title": "自宅などの地盤の安全性を簡単に知りたい",
			"faq3_title": null,
			"start": "2020-03-13 18:40:04"
	    },
	    ...
	]
  ```

## <a name="2"></a>2. Lấy thông tin ChatManage từ id
* **URL:** [{API_ROOT}/chatmanage/{id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {id} là mã của ChatManage id cần truyền vào

### Dữ liệu trả về:
```
	{
		"id": int,
		"user_id": int,
		"user_name": string,
		"user_email": string,
		"company_name": string,
		"staff_id": int,
		"staff_name": string,
		"faq1_id": int,
		"faq2_id": int,
		"faq3_id": int,
		"faq1_title": string,
		"faq2_title": string,
		"faq3_title": string,
		"start": datetime,
		"end": datetime
	}
```

##### Ví dụ: {API_ROOT}/chatmanage/2
Dữ liệu trả về:
```
	{
		"id": "2",
		"user_id": "2",
		"user_name": "User Chat 1",
		"user_email": "userchat1@gmail.com",
    	"company_name": "Company 1",
		"staff_id": "2",
		"staff_name": "Staff 1",
		"faq1_id": "1",
		"faq2_id": "2",
		"faq3_id": "0",
		"faq1_title": "個人のお客様",
		"faq2_title": "自宅などの地盤の安全性をかんたんに知りたい",
		"faq3_title": null,
		"start": "2019-11-20 18:44:46",
		"end": null
	}
```

## <a name="3"></a>3. Thêm 1 ChatManage mới
* **URL:** [{API_ROOT}/chatmanage](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"user_id": int,
		"staff_id": int,
		"faq1_id": int,
		"faq2_id": int,
		"faq3_id": int
	}
```
Trường hợp chưa có "staff_id" thì không cần truyền qua
### Dữ liệu trả về:
```
	{
	    "id": int,
		"user_id": int,
		"staff_id": int,
		"faq1_id": int,
		"faq2_id": int,
		"faq3_id": int
	}
```

##### Ví dụ: 
```
	{
		"id": "2",
		"user_id": "2",
		"staff_id": "2",
		"faq1_id": "1",
		"faq2_id": "2",
		"faq3_id": "0"
	}
```

## <a name="4"></a>4. Cập nhật ChatManage theo id
* **URL:** [{API_ROOT}/chatmanage/{id}](#)
* **Method:** PUT
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"user_id": int,
		"staff_id": int,
		"faq1_id": int,
		"faq2_id": int,
		"faq3_id": int
	}
```

### Dữ liệu trả về:
```
	{
		"contact_id": int,
		"manage_id": int,
		"user_id": int,
		"user_name": string,
		"user_email": string,
    	"company_name": string,
		"staff_id": int,
		"staff_name": string,
		"faq1_id": int,
		"faq2_id": int,
		"faq3_id": int,
		"faq1_title": string,
		"faq2_title": string,
		"faq3_title": string,
		"start": datetime,
		"end": datetime,
        "not_seen": int
	}
```

##### Ví dụ: 
```
	{
		"contact_id": "1",
		"manage_id": "328",
		"user_id": "358",
		"user_name": "p",
		"user_email": "p@p.pen",
		"company_name": "p",
		"staff_id": "2",
		"staff_name": "S2",
		"faq1_id": "1",
		"faq2_id": "2",
		"faq3_id": "0",
		"faq1_title": "個人のお客様",
		"faq2_title": "自宅などの地盤の安全性をかんたんに知りたい",
		"faq3_title": null,
		"start": "2019-11-29 16:28:36",
		"end": "2019-11-29 16:29:34",
		"not_seen": "1"
	}
```

## <a name="5"></a>5. Xóa ChatManage theo id
* **URL:** [{API_ROOT}/chatmanage/{id}](#)
* **Method:** DELETE
* **Content Type:** text/plain
* **Reponse Type:** text/json

### Tham số:
Truyền vào id của ChatManage cần xóa


##### Ví dụ: 
		URL: [{API_ROOT}/chatmanage/123

### Dữ liệu trả về:
- Xóa thành công: **Status code**= 200
- Xóa không thành công: **Status code** = 500


## <a name="6"></a>6. Cập nhật thời gian kết thúc ChatManage theo id
* **URL:** [{API_ROOT}/endchatmanage/{id}](#)
* **Method:** PUT
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
Không tham số

### Dữ liệu trả về:
code 200

## <a name="7"></a>7. Thêm 1 Client Request mới
* **URL:** [{API_ROOT}/clientrequest](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"family_name": string,
		"company_name": string,
		"email": string,
		"staff_id": int,
		"faq1_id": int,
		"faq2_id": int,
		"faq3_id": int
	}
```
Trường hợp chưa có "staff_id" thì không cần truyền tham số này qua
### Dữ liệu trả về:
```
	{
		"user_id": int,
		"manager_id": int
	}
```

##### Ví dụ: 
```
	{
		"user_id": "823",
		"manager_id": "836"
	}
```

## 8. Lấy danh sách ChatManage từ staff id
* **URL:** [{API_ROOT}/chatmanagesbystaffid/{[id]}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* {id} là mã của staff_id (admin) cần truyền vào
##### Ví dụ: 
		URL: [{API_ROOT}/chatmanagesbystaffid/2

### Dữ liệu trả về:
    
  ```
	[
	    {
		"contact_id": int,
		"manage_id": int,
		"user_id": int,
		"user_name": string,
		"user_email": string,
    	"company_name": string,
		"staff_id": int,
		"staff_name": string,
		"faq1_id": int,
		"faq2_id": int,
		"faq3_id": int,
		"faq1_title": string,
		"faq2_title": string,
		"start": datetime,
		"end": datetime,
        	"not_seen": int
	    },
	    {
		"contact_id": int,
		"manage_id": int,
		"user_id": int,
		"user_name": string,
		"user_email": string,
    		"company_name": string,
		"staff_id": int,
		"staff_name": string,
		"faq1_id": int,
		"faq2_id": int,
		"faq3_id": int,
		"faq1_title": string,
		"faq2_title": string,
		"start": datetime,
		"end": datetime,
        	"not_seen": int
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
		"contact_id": "1",
		"manage_id": "328",
		"user_id": "358",
		"user_name": "p",
		"user_email": "p@p.pen",
		"company_name": "p",
		"staff_id": "2",
		"staff_name": "S2",
		"faq1_id": "1",
		"faq2_id": "2",
		"faq3_id": "0",
		"faq1_title": "個人のお客様",
		"faq2_title": "自宅などの地盤の安全性をかんたんに知りたい",
		"faq3_title": null,
		"start": "2019-11-29 16:28:36",
		"end": "2019-11-29 16:29:34",
		"not_seen": "1"
	    },
	    {
		"contact_id": "2",
		"manage_id": "329",
		"user_id": "358",
		"user_name": "p",
		"user_email": "p@p.pen",
		"company_name": "p",
		"staff_id": "2",
		"staff_name": "S2",
		"faq1_id": "1",
		"faq2_id": "2",
		"faq3_id": "0",
		"faq1_title": "個人のお客様",
		"faq2_title": "自宅などの地盤の安全性をかんたんに知りたい",
		"faq3_title": null,
		"start": "2019-11-29 16:31:49",
		"end": "2019-11-29 16:32:48",
		"not_seen": "1"
	    },
	    ...
	]
  ```

## 9. Lấy danh sách users chat đã từng chat với staff
* **URL:** [{API_ROOT}/listuserschatwithstaff/{id}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* id ở đây là id của staff (admin)

##### Ví dụ: 
		URL: [{API_ROOT}/listuserschatwithstaff/47

### Dữ liệu trả về:
    
  ```
	[
	    {
			"user_id": int
	    },
	    {
			"user_id": int
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"user_id": "1"
	    },
	    {
			"user_id": "2"
	    },
	    ...
	]
  ```
