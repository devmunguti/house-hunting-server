# Backend API Endpoints

This document describes the API endpoints defined in `back/routes/api.php`, the HTTP methods they use, the expected request payloads, and the response data.

## Base API Path

The routes in `back/routes/api.php` are loaded by Laravel's API router. By default, these routes are served under the `/api` prefix.

Example full route URLs:
- `POST /api/auth/login`
- `POST /api/auth/register`
- `GET /api/user`
- `POST /api/logout`

---

## Authentication Endpoints

### `POST /api/auth/login`

Authenticate a user and return an access token.

Request body (JSON):
- `email` (string, required): valid email address.
- `password` (string, required): user password.

Example request:
```json
{
  "email": "user@example.com",
  "password": "secret123"
}
```

Success response (JSON):
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Example User",
    "email": "user@example.com",
    "role": "admin",
    "created_at": "2026-...",
    "updated_at": "2026-..."
  },
  "token": "<plain-text-sanitum-token>"
}
```

Error behavior:
- Invalid credentials return a validation exception with an error on the `email` field.
- Missing or invalid fields return standard Laravel validation errors.

---

### `POST /api/auth/register`

Create a new user account.

Request body (JSON):
- `name` (string, required): user full name, max 255 characters.
- `email` (string, required): valid email address, max 255 characters, must be unique in `users`.
- `password` (string, required): minimum 8 characters.
- `password_confirmation` (string, required): must match `password`.
- `role` (string, required): user role, max 255 characters.

Example request:
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "secret123",
  "password_confirmation": "secret123",
  "role": "agent"
}
```

Success response (JSON):
```json
{
  "success": true,
  "user": {
    "id": 2,
    "name": "New User",
    "email": "newuser@example.com",
    "role": "agent",
    "created_at": "2026-...",
    "updated_at": "2026-..."
  }
}
```

Error behavior:
- Validation errors occur when required fields are missing, `email` is invalid or already used, `password` and `password_confirmation` do not match, or `password` is too short.

---

## Authenticated User Endpoints

These routes are protected by `auth:sanctum` middleware and require a valid Sanctum bearer token:

Header:
- `Authorization: Bearer <token>`

### `GET /api/user`

Returns the authenticated user object.

Success response (JSON):
```json
{
  "id": 1,
  "name": "Example User",
  "email": "user@example.com",
  "role": "admin",
  "created_at": "2026-...",
  "updated_at": "2026-..."
}
```

If no valid token is provided, the request will be rejected with an authentication error.

---

### `POST /api/logout`

Revoke the current access token for the authenticated user.

Request body: none required.

Header:
- `Authorization: Bearer <token>`

Success response (JSON):
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## User Model Notes

From `back/app/Models/User.php`, the user model includes these assignable fields:
- `name`
- `email`
- `password`
- `role`

Hidden fields in serialized user responses:
- `password`
- `remember_token`

The password is automatically hashed on save using Laravel's `hashed` cast.

---

## Summary of Routes

| Route | Method | Auth Required | Request body | Response |
|---|---|---|---|---|
| `/api/auth/login` | POST | No | `email`, `password` | `success`, `user`, `token` |
| `/api/auth/register` | POST | No | `name`, `email`, `password`, `password_confirmation`, `role` | `success`, `user` |
| `/api/user` | GET | Yes | none | authenticated `user` |
| `/api/logout` | POST | Yes | none | `success`, `message` |
