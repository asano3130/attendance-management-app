# attendance-management-app

## 環境構築

1.DockerDesktopアプリを起動

2.Dockerコンテナ作成
docker-compose up -d --build

##Laravel環境構築
1.PHPコンテナへ入る
docker-compose exec php bash
2.composer install
composer install
3..env作成
cp .env.example .env 4.アプリケーションキー作成
php artisan key:generate 5.マイグレーション実行
php artisan migrate 6.シーディング実行
php artisan db:seed

##使用技術
PHP 8.1
Laravel 8.x
MySQL 8.0
Docker
Laravel Fortify

##ER図
![ER図](.src/public/images/readme/er.png)
##機能一覧
一般ユーザー
ログイン機能
ログアウト機能
出勤機能
退勤機能
休憩開始機能
休憩終了機能
勤怠一覧表示
勤怠詳細表示
修正申請機能
申請一覧表示
管理者
ログイン機能
ログアウト機能
日次勤怠一覧表示
勤怠詳細修正機能
スタッフ一覧表示
スタッフ別月次勤怠一覧表示
CSV出力機能
修正申請承認機能

## テーブル設計

### usersテーブル

| カラム名          | 型        | PRIMARY KEY | UNIQUE | NOT NULL | FOREIGN KEY |
| ----------------- | --------- | ----------- | ------ | -------- | ----------- |
| id                | bigint    | ○           |        | ○        |             |
| name              | string    |             |        | ○        |             |
| email             | string    |             | ○      | ○        |             |
| password          | string    |             |        | ○        |             |
| email_verified_at | timestamp |             |        |          |             |
| created_at        | timestamp |             |        |          |             |
| updated_at        | timestamp |             |        |          |             |

---

### adminsテーブル

| カラム名   | 型        | PRIMARY KEY | UNIQUE | NOT NULL | FOREIGN KEY |
| ---------- | --------- | ----------- | ------ | -------- | ----------- |
| id         | bigint    | ○           |        | ○        |             |
| name       | string    |             |        | ○        |             |
| email      | string    |             | ○      | ○        |             |
| password   | string    |             |        | ○        |             |
| created_at | timestamp |             |        |          |             |
| updated_at | timestamp |             |        |          |             |

---

### attendancesテーブル

| カラム名   | 型        | PRIMARY KEY | NOT NULL | FOREIGN KEY |
| ---------- | --------- | ----------- | -------- | ----------- |
| id         | bigint    | ○           | ○        |             |
| user_id    | bigint    |             | ○        | users.id    |
| date       | date      |             | ○        |             |
| clock_in   | datetime  |             |          |             |
| clock_out  | datetime  |             |          |             |
| status     | string    |             | ○        |             |
| created_at | timestamp |             |          |             |
| updated_at | timestamp |             |          |             |

---

### breaksテーブル

| カラム名      | 型        | PRIMARY KEY | NOT NULL | FOREIGN KEY    |
| ------------- | --------- | ----------- | -------- | -------------- |
| id            | bigint    | ○           | ○        |                |
| attendance_id | bigint    |             | ○        | attendances.id |
| break_start   | datetime  |             |          |                |
| break_end     | datetime  |             |          |                |
| created_at    | timestamp |             |          |                |
| updated_at    | timestamp |             |          |                |

---

### attendance_correction_requestsテーブル

| カラム名            | 型        | PRIMARY KEY | NOT NULL | FOREIGN KEY    |
| ------------------- | --------- | ----------- | -------- | -------------- |
| id                  | bigint    | ○           | ○        |                |
| user_id             | bigint    |             | ○        | users.id       |
| attendance_id       | bigint    |             | ○        | attendances.id |
| requested_clock_in  | datetime  |             |          |                |
| requested_clock_out | datetime  |             |          |                |
| note                | text      |             | ○        |                |
| status              | string    |             | ○        |                |
| approved_at         | datetime  |             |          |                |
| created_at          | timestamp |             |          |                |
| updated_at          | timestamp |             |          |                |

##テストアカウント
一般ユーザー
名前 メールアドレス パスワード
山田太郎 yamada@example.com password

管理者
メールアドレス パスワード
admin@example.com password

##URL
開発環境：http://localhost:81/
phpMyAdmin：http://localhost:8080/
