# :page_facing_up: Todo-list API in Laravel :page_facing_up:

[![Tech stack](https://skillicons.dev/icons?i=php,laravel,mysql,redis,docker,gcp)](https://skillicons.dev)

---

## :rocket: Features :rocket:
- **Category**:
    - :bulb: Add list category
    - :bulb: Remove list category
    - :bulb: Get all `category`
    - :bulb: Get a specific `category` by `id`
- **List**:
    - :bulb: Add `list` in a `category`
    - :bulb: Remove `list` from a `category`
    - :bulb: Update `list` in a `category`
    - :bulb: Get all `list` from a specific `category`
    - :bulb: Get a specific `list` by `id`
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
- **Images**:
    - :bulb: Upload `list` background
    - :bulb: Remove `list` background
    - :bulb: Upload `item` banner
    - :bulb: Remove `item` banner

---

## 🗝️ Authentication 🗝️

The authentication is based on Bearer JWT tokens. A middleware is applied to every modifying operation to prevent unauthorized changes.

A default user has been added to the system. See `database/DatabaseSeeder.php`.

---

## 💾 Installation 💾

If you don't know how  `laravel sail` work, then feel free to check it in `laravel documentation` at the following link: https://laravel.com/docs/11.x/sail

1. Create `.env` file based on `.env.example`. Pay close attention to the instructions which marked with **"TODO"**!

2. Run `composer install` in the base folder

3. Start `sail` docker containers with the following command:
    ```batch
    ./vendor/bin/sail up -d --build
    ```

4. Jump into `container` shell:
    ```batch
    docker exec -it todo-list-todo-app-1 /bin/bash
    ```

5. Install `composer` packages within the `container`:
    ```batch
     composer install
    ```

6. Generate `app-key` with `artisan` keygen within `container` shell:
    ```batch
    php artisan key:generate
    ```

7. Generate `jwt-secret` with `artisan` jwt generator within `container` shell:
    ```batch
    php artisan jwt:secret
    ```

8. Run  `migrations` with seeder in the `container` shell:
    ```batch
    php artisan migrate:fresh --seed
    ```

9. Regenerate `container` in the base shell:
    ```batch
    ./vendor/bin/sail down
    ./vendor/bin/sail up -d --build
    ```

---

## 🧮 Status codes 🧮

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 201 | `CREATED` |
| 400 | `BAD REQUEST` |
| 404 | `NOT FOUND` |
| 422 | `UNPROCESSABLE CONTENT` |
| 500 | `INTERNAL SERVER ERROR` |

---

## 🤕 Header requirements 🤕

The following informations must also be sent in the header:

```
Accept:        application/json
Content-type:  application/json
```

The header's `Content-type` parameter is mostly `application/json`. If it's not, it will be mentioned at the endpoint description.

---

## 👆 Endpoints 👇

### Categories

#### Get Categories

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
*Response Status*: `200`
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
*Response Status*: `404`
*Response Body*: 
```json
{
	"message": "The category with the specified ID was not found."
}
```

#### Insert new category

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

#### Delete existing category

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
---
### Lists

#### Get Lists
Returns all lists of the logged in user

*Type*: **GET**
*URI*: `/api/lists`
*Response format*: `JSON`
*URL parameters*:
| Parameter | Type | Description |
| --------- | ---- | ----------- |
| id | integer (optional) | If you want to get a specific list by ID |

*Query parameters*:
| Parameter | Type | Description |
| --------- | ---- | ----------- |
| category_id | integer (optional) | ID of an existing category |

**Lists found**
*Request URI*: `/api/lists`
*Response Status*: `200`
*Response Body*: 
```json
{
	"message": "The lists fetched successfully",
	"lists": [
		{
			"id": 2,
			"title": "Teszt list #1",
			"description": "Test description",
			"is_completed": 0,
			"completed_at": null,
			"due_date": null,
			"category": {
				"id": 1,
				"title": "test3"
			}
		},
		{
			"id": 3,
			"title": "Untitled",
			"description": "Test description",
			"is_completed": 0,
			"completed_at": null,
			"due_date": null,
			"category": {
				"id": 1,
				"title": "test3"
			}
		}
	]
}
```

**Empty lists**
*Request URI*: `/api/lists`
*Response Status*: `200`
*Response Body*: 
```json
{
	"message": "The lists fetched successfully",
	"categories": []
}
```

**List found**
*Request URI*: `/api/lists/2`
*Response Status*: `200`
*Response Body*: 
```json
{
	"message": "The list fetched successfully",
	"lists": {
		"id": 2,
		"category_id": 1,
		"title": "Test list #1",
		"description": "Test description",
		"is_completed": 0,
		"completed_at": null,
		"due_date": null
	}
}
```

**List not found**
*Request URI*: `/api/lists/1`
*Response Status*: `404`
*Response Body*: 
```json
{
	"message": "The list with the specified ID was not found."
}
```

#### Insert new list
Insert new list to the logged in user

*Type*: **PUT**
*URI*: `/api/lists`
*Request format:* `JSON`
*Response format*: `JSON`
*Request body:*
```json
{
	"title": "<string>",
	"description": "<string>",
	"category_id": <integer>
}
```

**List inserted succesfully**
*Request URI*: `/api/lists`
*Response Status*: `201`
*Request Body*:
```json
{
	"title": "asd",
	"description": "Test description",
	"category_id": 1
}
```
*Response Body*: 
```json
{
	"message": "The list was created successfully",
	"inserted_list": {
		"id": 15,
		"title": "asd",
		"description": "Test description",
		"is_completed": 0,
		"completed_at": null,
		"due_date": null,
		"category": {
			"id": 1,
			"title": "test3"
		}
	}
}
```

#### Delete existing list
Deletes a list of the logged in user

*Type*: **DELETE**
*URI*: `/api/lists/{id}`
*Request format:* `JSON`
*Response format*: `JSON`
*URL parameters*:
| Parameter | Type | Description |
| --------- | ---- | ----------- |
| id | integer (optional) | ID of the list |

**List deleted succesfully**
*Request URI*: `/api/lists/1`
*Response Status*: `200`

```json
{
	"message": "The list deleted successfully",
	"deleted_list": 1
}
```

**List not found**
*Request URI*: `/api/lists/2`
*Response Status*: `201`

```json
{
	"message": "The list with the specified ID was not found."
}
```
