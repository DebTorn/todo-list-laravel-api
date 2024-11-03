# Todo-list CHANGELOG

## 2024. 11. 03
- Refactor `title` in `TodoListFactory`
- Added `item` validation check to `ItemService -> updateItem` method
- Created `Items` section in the readme
- Refactor `update` method in `ItemController`

## 2024. 11. 02
- Added `Category, Item and TodoList` factories
- Removed `lists_items` migration
- Added Feature tests for item endpoints

## 2024. 11. 01
- Added `Item` model with `migrations`
- Added item `services` and `repositories`
- Added `ItemController`, `routes` and `requests`
- Bind new `services` and `repositories`
- Refactor `ListController`
- Refactor `CategoryRepository` and `ListRepository`

## 2024. 10. 24
### @tbence
- Added `JWT authentication`
- Added `uuid` to users
- Added `middleware protection` to `CategoryController`
- Extended `README` with `jwt keygen`
- Added `list` endpoints
- Extended `list` endpoints with `jwt auth`
- Added `redis based` countdown and try check

## 2024. 10. 23
### @tbence
- Added `category` endpoints (index, store, delete)
- Added `redis` and `redis-commander` containers
- Added requests: `DeleteCategoryRequest`, `StoreCategoryRequest`
- Added `Service - Repository` layers with `providers`
- Added new migration for `categories` table
