### API public

**API_ROOT:** https://jiban.earth/jibannet-callcenter-api

## Danh sách các API liên quan tới Analytic
###### 1. [Thống kê toàn bộ ChatManage theo chủ đề](#1-lấy-toàn-bộ-chatmanage-1)
###### 2. [Lấy thông tin ChatManage từ id](#2-lấy-thông-tin-chatmanage-từ-id-1)

**********************************

## Danh sách các API liên quan tới Analytic
## 1. Thống kê toàn bộ ChatManage theo chủ đề
* **URL:** [{API_ROOT}/totalchatmanagebytheme](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

##### Ví dụ: 
		URL: [{API_ROOT}/totalchatmanagebytheme

### Dữ liệu trả về:
    
  ```
	[
	    {
			"theme_id": int,
			"theme_name": int,
			"total": int
	    },
	    {
			"theme_id": int,
			"theme_name": int,
			"total": int
	    },
	    ...
	]
  ```

###### Ví dụ:
```
	[
	    {
			"theme_id": "2",
			"theme_name": "地盤安心住宅の進捗",
			"total": "9"
	    },
	    {
			"theme_id": "5",
			"theme_name": "その他",
			"total": "7"
	    },
	    ...
	]
  ```

## <a name="2"></a>2. Thống kê ChatManage theo thời gian
* **URL:** [{API_ROOT}/totalchatmanagebydate?from_date={from_date}](#)
* **Method:** GET
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
* from_date là ngày bắt đầu cần truyền vào theo format YYYY-MM-DD

### Dữ liệu trả về:
```
	[
    {
      "datetime": datetime,
      "total": int
    },
    ...
  ]
```

##### Ví dụ: {API_ROOT}/totalchatmanagebydate?from_date=2020-02-7
Dữ liệu trả về:
```
	[
	    {
			"datetime": "2020-03-04",
			"total": "9"
	    },
	    {
			"datetime": "2020-03-10",
			"total": "7"
	    },
	    ...
	]
```
