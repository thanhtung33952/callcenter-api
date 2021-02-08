### API public

**API_ROOT:** http://133.242.244.84/jibannet-callcenter-api

## Danh sách các API liên quan tới ChatUser
###### 1. [Lấy lịch sử chat bởi user (tối đa 3)](#1. Lấy lịch sử chat bởi user (tối đa 3))
###### 2. [Lấy lịch sử chat bởi company (tối đa 12)](#2. Lấy lịch sử chat bởi company (tối đa 12))
###### 3. [Lấy lịch sử chat bởi keywword nội dung chat](#3. Lấy lịch sử chat bởi keywword nội dung chat)
###### 4. [Lấy lịch sử chat bởi faq](#4. Lấy lịch sử chat bởi  faq)

**********************************

## Danh sách các API liên quan tới ChatUser

## <a name="1"></a>1. Lấy lịch sử chat bởi user (tối đa 3)
* **URL:** [{API_ROOT}/chathistorybyuser](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"email": string,
		"family_name": string,
		"company_name": string
	}
```

### Dữ liệu trả về:
```
[
	{
        "id": int,
        "user_id": int,
        "email": string,
        "family_name": string,
        "company_id": int,
        "staff_id": int,
        "faq1_id": int,
        "faq2_id": int,
        "faq3_id": int,
        "start": datetime,
        "end": datetime
	},
	...
]
```

##### Ví dụ: 
```
[
	{
        "id": "138",
        "user_id": "187",
        "email": "khoa@gmail.com",
        "family_name": "khoa",
        "company_id": "136",
        "staff_id": "1",
        "faq1_id": "1",
        "faq2_id": "2",
        "faq3_id": "2",
        "start": "2019-11-26 15:15:39",
        "end": null
	},
	...
]
```

## <a name="2"></a>2. Lấy lịch sử chat bởi company (tối đa 12)
* **URL:** [{API_ROOT}/chathistorybycompany](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
		"company_name": string
	}
```

### Dữ liệu trả về:
```
[
	{
        "id": int,
        "user_id": int,
        "email": string,
        "family_name": string,
        "company_id": int,
        "staff_id": int,
        "faq1_id": int,
        "faq2_id": int,
        "faq3_id": int,
        "start": datetime,
        "end": datetime
	},
	...
]
```

##### Ví dụ: 
```
[
	{
        "id": "138",
        "user_id": "187",
        "email": "khoa@gmail.com",
        "family_name": "khoa",
        "company_id": "136",
        "staff_id": "1",
        "faq1_id": "1",
        "faq2_id": "2",
        "faq3_id": null,
        "start": "2019-11-26 15:15:39",
        "end": null
	},
	...
]
```

## <a name="3"></a>3. Lấy lịch sử chat bởi keywword nội dung chat
* **URL:** [{API_ROOT}/chathistorybykeysearch/[{keyword}]](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
keyword: nội dung cần tìm kiếm


### Dữ liệu trả về:
```
{
    "companyData": [
        {
            "id_chat": int,
            "company_name": string,
            "user_name": string,
            "faq1_title": string,
            "faq2_title": string,
            "faq3_title": string,
            "start_date": datetime,
            "end_date": datetime
        },
        ...
    ],
    "userData": [
        {
            "id_chat": int,
            "company_name": string,
            "user_name": string,
            "faq1_title": string,
            "faq2_title": string,
            "faq3_title": string,
            "start_date": datetime,
            "end_date": datetime
        },
        ...
    ],
    "categoryData": [
        {
            "id_chat": int,
            "company_name": string,
            "user_name": string,
            "faq1_title": string,
            "faq2_title": string,
            "start_date": datetime,
            "end_date": datetime
        },
        ...
    ]
}
```

##### Ví dụ: 
```
{
    "companyData": [
        {
            "id_chat": "586",
            "company_name": "bb",
            "user_name": "bb",
            "faq1_title": "個人のお客様",
            "faq2_title": "自宅などの地盤の安全性をかんたんに知りたい",
            "faq3_title": null,
            "start_date": "2019-12-04 19:22:33",
            "end_date": "2019-12-04 19:31:16"
        },
        ...
    ],
    "userData": [
        {
            "id_chat": "586",
            "company_name": "bb",
            "user_name": "bb",
            "faq1_title": "個人のお客様",
            "faq2_title": "自宅などの地盤の安全性をかんたんに知りたい",
            "faq3_title": null,
            "start_date": "2019-12-04 19:22:33",
            "end_date": "2019-12-04 19:31:16"
        },
        ...
    ],
    "categoryData": [
        {
            "id_chat": "586",
            "company_name": "bb",
            "user_name": "bb",
            "faq1_title": "個人のお客様",
            "faq2_title": "自宅などの地盤の安全性をかんたんに知りたい",
            "start_date": "2019-12-04 19:22:33",
            "end_date": "2019-12-04 19:31:16"
        },
        ...
    ]
}
```

## <a name="4"></a>4. Lấy lịch sử chat bởi faq
* **URL:** [{API_ROOT}/chathistorybyfaq](#)
* **Method:** POST
* **Content Type:** application/json
* **Reponse Type:** text/json

### Tham số:
```
	{
        "staff_id": int,
        "faq1_id": int,
        "faq2_id": int,
        "faq3_id": int
	}
```

### Dữ liệu trả về:
```
[
	{
        "id": int,
        "user_id": int,
        "email": string,
        "family_name": string,
        "company_id": int,
        "staff_id": int,
        "faq1_id": int,
        "faq2_id": int,
        "faq3_id": int,
        "start": datetime,
        "end": datetime
	},
	...
]
```

##### Ví dụ: 
```
[
	{
        "id": "138",
        "user_id": "187",
        "email": "khoa@gmail.com",
        "family_name": "khoa",
        "company_id": "136",
        "staff_id": "1",
        "faq1_id": "1",
        "faq2_id": "2",
        "faq3_id": "2",
        "start": "2019-11-26 15:15:39",
        "end": null
	},
	...
]
```
