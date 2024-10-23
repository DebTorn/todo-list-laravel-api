# :page_facing_up: Todo-list API in Laravel :page_facing_up:

[![Tech stack](https://skillicons.dev/icons?i=php,laravel,mysql,docker)](https://skillicons.dev)

---

## Table of contents
-   [Installation](#installation)
-   [Header requirements](#header-requirements)
-   [Endpoints](#endpoints)

---

## :rocket: Features :rocket:
- **Category**:
    - :bulb: Add list category
    - :bulb: Remove list category
    - :bulb: Get all `category`
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

## Header requirements

The following informations must also be sent in the header:

```
Accept:        application/json
Content-type:  application/json
X-language:    <language_code>
```

The header's `Content-type` parameter is mostly `application/json`. If it's not, it will be mentioned at the endpoint description.

---

## Endpoints


