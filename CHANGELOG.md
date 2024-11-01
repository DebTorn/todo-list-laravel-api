# Todo-list CHANGELOG

## 2024. 11. 01
- Added `Item` model with `migrations`
- Added item `services` and `repositories`

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
