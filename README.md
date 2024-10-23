# :page_facing_up: Todo-list API in Laravel :page_facing_up:

[![Tech stack](https://skillicons.dev/icons?i=php,laravel,mysql,redis,docker)](https://skillicons.dev)

---

## Table of contents
-   [Installation](#installation)
-   [Status codes](#status-codes)
-   [Header requirements](#header-requirements)
-   [Endpoints](#endpoints)

---

## :rocket: Features :rocket:
- **Category**:
    - :bulb: Add list category
    - :bulb: Remove list category
    - :bulb: Get all `category`
    - :bulb: Get a specific `category` by `id`
- **List**:
    - :bulb: Add `list` in a `category`
    - :bulb: Archive `list` in  a `category`
    - :bulb: Remove `list` from a `category`
    - :bulb: Update `list` in a `category`
    - :bulb: Get all `list` from a specific `category`
    - :bulb: Get a specific `list` by `id` from a specific `category`
- **Item**:
    - :bulb: Add `item` to a `list`
    - :bulb: Remove `item` from a `list`
    - :bulb: Get all `item` from a `list`
    - :bulb: Get a specific `item` by `id` from a specific `list`
- **Sub-item**:
    - :bulb: Add `sub-item` to an `item`
    - :bulb: Remove `sub-item` from an `item`
    - :bulb: Get all `sub-item` from an `item`
    - :bulb: Get `sub-item` by `id` from a specific `item`

---

## Installation

1. Create `.env` file based on `.env.example`. Pay close attention to the instructions which marked with **"TODO"**!

2. Start `sail` docker containers with the following command:
    ```batch
    ./vendor/bin/sail up -d --build
    ```

3. Jump into `container` shell:
    ```batch
    docker exec -it todo-list-todo-app-1 /bin/bash
    ```

4. Install `composer` packages within the `container`:
    ```batch
     composer install
    ```

5. Run  `migrations` with artisan in the `container` shell:
    ```batch
    php artisan migrate
    ```

6. Generate `app-key` with `artisan` keygen within `container` shell:
    ```batch
    php artisan key:generate
    ```

7. Regenerate `container` in the base shell:
    ```batch
    ./vendor/bin/sail down
    ./vendor/bin/sail up -d --build
    ```

---

## Status codes

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 201 | `CREATED` |
| 400 | `BAD REQUEST` |
| 404 | `NOT FOUND` |
| 422 | `UNPROCESSABLE CONTENT` |
| 500 | `INTERNAL SERVER ERROR` |

---

## Header requirements

The following informations must also be sent in the header:

```
Accept:        application/json
Content-type:  application/json
```

The header's `Content-type` parameter is mostly `application/json`. If it's not, it will be mentioned at the endpoint description.

---

## Endpoints

### Get Categories

Returns all category or a specific one by ID

*Type*: **GET**
*URI*: `/api/category`
*Response format*: `JSON`
*URL parameters*:
| Parameter | Type | Description |
| --------- | ---- | ----------- |
| id | integer (optional) | If you want to get a specific category by ID |

**Categories found**
*Request URI*: `/api/category`
*Response Status*: `200`
*Response Body*: 
```json
{
	"message": "The categories fetched successfully",
	"categories": [
		{
			"id": 2,
			"title": "teszt2"
		}
	]
}
```

**Empty categories**
*Request URI*: `/api/category`
*Response Status*: `200`
*Response Body*: 
```json
{
	"message": "The categories fetched successfully",
	"categories": []
}
```

**Category found**
*Request URI*: `/api/category/2`
*Response Status*: `500`
*Response Body*: 
```json
{
	"message": "The category fetched successfully",
	"categories": {
		"id": 2,
		"title": "teszt2"
	}
}
```

**Category not found**
*Request URI*: `/api/category/1`
*Response Status*: `500`
*Response Body*: 
```json
{
	"message": "The category with the specified ID was not found."
}
```

### Insert new category

Insert new category to the database

*Type*: **PUT**
*URI*: `/api/category`
*Request format:* `JSON`
*Response format*: `JSON`
*Request body:*
```json
{
    "title": "<title> - string "
}
```

**Category inserted succesfully**
*Request URI*: `/api/category`
*Response Status*: `201`
*Request Body*:
```json
{
    "title": "test_category_name"
}
```
*Response Body*: 
```json
{
    "message": "The category created successfully",
    "inserted_category": {
        "title": "test_category_name",
        "id": 3
    }
}
```

**Category already taken**
*Request URI*: `/api/category`
*Response Status*: `422`
*Request Body*:
```json
{
    "title": "test_category_name"
}
```
*Response Body*: 
```json
{
	"message": "The title has already been taken.",
	"errors": {
		"title": [
			"The title has already been taken."
		]
	}
}
```

### Delete existing category

Remove an existing category from database.

If you want to delete a category, then you need to extends the `request body` with the correct title of the category what the id matched.

*Type*: **DELETE**
*URI*: `/api/category`
*Request format:* `JSON`
*Response format*: `JSON`
*Request body:*
```json
{
    "id": "<id> - integer",
    "title": "<title> - string"
}
```

**Category deleted succesfully**
*Request URI*: `/api/category`
*Response Status*: `200`
*Request Body*:
```json
{
    "id": 3,
    "title": "test_category_name"
}
```

*Response Body*: 
```json
{
	"message": "The category deleted successfully",
	"deleted_category": 3
}
```

**Title mismatch before deleting**

**IMPORTANT**: Only 2 try available, then you need to wait 30 sec

*Request URI*: `/api/category`
*Response Status*: `500`
*Request Body*:
```json
{
    "id": 3,
    "title": "test_category_name"
}
```

*Response Body*: 
```json
{
	"message": "The title does not match the category title. Please try again."
}
```

**Category not found**
*Request URI*: `/api/category`
*Response Status*: `201`
*Request Body*:
```json
{
    "id": 3,
    "title": "test_category_name"
}
```

*Response Body*:
```json
{
	"message": "The category with the specified ID was not found."
}
```
