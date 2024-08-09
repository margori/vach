# V.A.C.H.

Assessment Tool for Organizational Coaching

Licence: [GNU AFFERO GENERAL PUBLIC LICENSE](https://www.gnu.org/licenses/agpl-3.0.en.html)

## Version requirements

- PHP >= 7.4
- MySQL 5.7, 8

## Structure of Spreadsheet to import users

| A      | B       | C      | D      |
| ------ | ------- | ------ | ------ |
| name   | surname | email  | phone  |
| string | string  | string | string |

Expected column header in file 1, user's data from line 2.

## Util terminal commands

Extract messages for locales

```
$ docker exec -ti vach-web bash
$ ./yii message/extract config/message.php
```

Mysql restore db dump:

```
$ mysql -h localhost -P 10183 --protocol=tcp -u root -p -e "CREATE DATABASE vach_production CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
$ mysql -h localhost -P 10183 --protocol=tcp -u root -p vach_production < coachcpc_vach.2024-01-01.sql
```

Disable emails and set passwords to '123456':

```
UPDATE user SET email = "admin@example.com.ar", password_hash = "$2y$13$rXZdcXiqPgKGroqyFrYrhuFsuGhEb1OtpGuhtjzNDGelTl3V0iS52"
```
