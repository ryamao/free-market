# テーブル設計

## users テーブル

| カラム名          | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| ----------------- | --------------- | ----------- | ---------- | -------- | ----------- |
| id                | unsigned bigint | ○           | ○          | ○        |             |
| email             | varchar(255)    |             | ○          | ○        |             |
| email_verified_at | timestamp       |             |            |          |             |
| password          | varchar(255)    |             |            | ○        |             |
| name              | varchar(255)    |             |            |          |             |
| image_url         | varchar(255)    |             |            |          |             |
| postcode          | varchar(255)    |             |            |          |             |
| prefecture        | varchar(255)    |             |            |          |             |
| address           | varchar(255)    |             |            |          |             |
| building          | varchar(255)    |             |            |          |             |
| created_at        | timestamp       |             |            |          |             |
| updated_at        | timestamp       |             |            |          |             |
| deleted_at        | timestamp       |             |            |          |             |
| stripe_id         | varchar(255)    |             |            |          |             |
| pm_type           | varchar(255)    |             |            |          |             |
| pm_last_four      | varchar(4)      |             |            |          |             |
| trial_ends_at     | timestamp       |             |            |          |             |

## items テーブル

| カラム名     | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY    |
| ------------ | --------------- | ----------- | ---------- | -------- | -------------- |
| id           | unsigned bigint | ○           | ○          | ○        |                |
| seller_id    | unsigned bigint |             |            | ○        | users(id)      |
| condition_id | unsigned bigint |             |            | ○        | conditions(id) |
| name         | varchar(255)    |             |            | ○        |                |
| image_url    | varchar(255)    |             |            | ○        |                |
| description  | text            |             |            | ○        |                |
| price        | unsigned int    |             |            | ○        |                |
| sold_at      | datetime        |             |            |          |                |
| created_at   | timestamp       |             |            |          |                |
| updated_at   | timestamp       |             |            |          |                |

## conditions テーブル

| カラム名   | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| ---------- | --------------- | ----------- | ---------- | -------- | ----------- |
| id         | unsigned bigint | ○           | ○          | ○        |             |
| name       | varchar(255)    |             | ○          | ○        |             |
| created_at | timestamp       |             |            |          |             |
| updated_at | timestamp       |             |            |          |             |

## categories テーブル

| カラム名   | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| ---------- | --------------- | ----------- | ---------- | -------- | ----------- |
| id         | unsigned bigint | ○           | ○          | ○        |             |
| name       | varchar(255)    |             | ○          | ○        |             |
| created_at | timestamp       |             |            |          |             |
| updated_at | timestamp       |             |            |          |             |

## item_category テーブル

| カラム名    | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY    |
| ----------- | --------------- | ----------- | ---------- | -------- | -------------- |
| item_id     | unsigned bigint | ○           | ○          | ○        | items(id)      |
| category_id | unsigned bigint | ○           | ○          | ○        | categories(id) |
| created_at  | timestamp       |             |            |          |                |
| updated_at  | timestamp       |             |            |          |                |

## favorites テーブル

| カラム名   | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| ---------- | --------------- | ----------- | ---------- | -------- | ----------- |
| user_id    | unsigned bigint | ○           | ○          | ○        | users(id)   |
| item_id    | unsigned bigint | ○           | ○          | ○        | items(id)   |
| created_at | timestamp       |             |            |          |             |
| updated_at | timestamp       |             |            |          |             |

## comments テーブル

| カラム名    | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| ----------- | --------------- | ----------- | ---------- | -------- | ----------- |
| id          | unsigned bigint | ○           | ○          | ○        |             |
| user_id     | unsigned bigint |             |            | ○        | users(id)   |
| item_id     | unsigned bigint |             |            | ○        | items(id)   |
| description | text            |             |            | ○        |             |
| created_at  | timestamp       |             |            |          |             |
| updated_at  | timestamp       |             |            |          |             |
| deleted_at  | timestamp       |             |            |          |             |

## purchases テーブル

| カラム名          | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| ----------------- | --------------- | ----------- | ---------- | -------- | ----------- |
| id                | unsigned bigint | ○           | ○          | ○        |             |
| user_id           | unsigned bigint |             |            | ○        | users(id)   |
| item_id           | unsigned bigint |             |            | ○        | items(id)   |
| payment_intent_id | varchar(255)    |             |            | ○        |             |
| payment_status    | varchar(255)    |             |            | ○        |             |
| client_secret     | varchar(255)    |             |            | ○        |             |
| paid_at           | datetime        |             |            |          |             |
| created_at        | timestamp       |             |            |          |             |
| updated_at        | timestamp       |             |            |          |             |

## admins テーブル

| カラム名       | 型              | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
| -------------- | --------------- | ----------- | ---------- | -------- | ----------- |
| id             | unsigned bigint | ○           | ○          | ○        |             |
| email          | varchar(255)    |             | ○          | ○        |             |
| password       | varchar(255)    |             |            | ○        |             |
| remember_token | varchar(100)    |             |            |          |             |
| created_at     | timestamp       |             |            |          |             |
| updated_at     | timestamp       |             |            |          |             |
