
INSERT INTO authors SET `id` = '1', `name` = 'Paolo', `deleted_at` = NULL, `created_at` = '2024-03-20 00:51:42', `updated_at` = '2024-03-20 00:51:42';
INSERT INTO authors SET `id` = '2', `name` = 'John Doe', `deleted_at` = NULL, `created_at` = '2024-03-20 00:52:25', `updated_at` = '2024-03-20 00:52:25';
INSERT INTO authors SET `id` = '3', `name` = 'Obama Barrack', `deleted_at` = NULL, `created_at` = '2024-03-20 00:52:34', `updated_at` = '2024-03-20 00:52:34';
INSERT INTO categories SET `id` = '1', `category` = 'BSIT', `deleted_at` = NULL, `created_at` = '2024-03-20 00:51:49', `updated_at` = '2024-03-20 00:51:49';
INSERT INTO categories SET `id` = '2', `category` = 'BSBA', `deleted_at` = NULL, `created_at` = '2024-03-20 00:51:53', `updated_at` = '2024-03-20 00:51:53';
INSERT INTO categories SET `id` = '3', `category` = 'BSHM', `deleted_at` = NULL, `created_at` = '2024-03-20 00:52:20', `updated_at` = '2024-03-20 00:52:20';
INSERT INTO keywords SET `id` = '1', `keyword` = 'English', `deleted_at` = NULL, `created_at` = '2024-03-20 00:52:01', `updated_at` = '2024-03-20 00:52:01';
INSERT INTO keywords SET `id` = '2', `keyword` = 'Math', `deleted_at` = NULL, `created_at` = '2024-03-20 00:52:05', `updated_at` = '2024-03-20 00:52:05';
INSERT INTO keywords SET `id` = '3', `keyword` = 'Science', `deleted_at` = NULL, `created_at` = '2024-03-20 00:52:11', `updated_at` = '2024-03-20 00:52:11';
INSERT INTO migrations SET `id` = '1', `migration` = '2014_10_12_000000_create_users_table', `batch` = '1';
INSERT INTO migrations SET `id` = '2', `migration` = '2019_12_14_000001_create_personal_access_tokens_table', `batch` = '1';
INSERT INTO migrations SET `id` = '3', `migration` = '2024_02_23_081002_create_theses_table', `batch` = '1';
INSERT INTO migrations SET `id` = '4', `migration` = '2024_02_23_081026_create_keywords_table', `batch` = '1';
INSERT INTO migrations SET `id` = '5', `migration` = '2024_02_23_081040_create_categories_table', `batch` = '1';
INSERT INTO migrations SET `id` = '6', `migration` = '2024_02_23_081101_create_authors_table', `batch` = '1';
INSERT INTO migrations SET `id` = '7', `migration` = '2024_02_26_001938_create_pivot_tables', `batch` = '1';
INSERT INTO theses SET `id` = '4', `pdf` = '', `video` = '', `title` = 'test4', `abstract` = 'qweqweqwe', `published_at` = '0011-11-11', `categories` = '[3,2,1]', `keywords` = '[3,2,1]', `authors` = '[3,2,1]', `deleted_at` = NULL, `created_at` = '2024-03-20 05:28:27', `updated_at` = '2024-03-20 05:28:27';
INSERT INTO theses SET `id` = '5', `pdf` = '', `video` = '', `title` = 'trest5', `abstract` = 'wqeqwe', `published_at` = '4444-04-04', `categories` = '[null,null]', `keywords` = '[3,1]', `authors` = '[null,null]', `deleted_at` = NULL, `created_at` = '2024-03-20 05:31:34', `updated_at` = '2024-03-20 07:07:16';
INSERT INTO theses SET `id` = '6', `pdf` = '', `video` = '', `title` = 'test6', `abstract` = 'qweqwewqe', `published_at` = '2222-02-20', `categories` = '[3,2]', `keywords` = '[3,2,1]', `authors` = '[1,2,3]', `deleted_at` = NULL, `created_at` = '2024-03-20 06:05:50', `updated_at` = '2024-03-25 00:35:24';
INSERT INTO theses SET `id` = '7', `pdf` = '', `video` = '', `title` = 'test 7', `abstract` = 'qweqwe', `published_at` = '2222-11-21', `categories` = '[3,2]', `keywords` = '[3,2]', `authors` = '[3,2]', `deleted_at` = NULL, `created_at` = '2024-03-20 06:17:26', `updated_at` = '2024-03-20 06:47:59';
INSERT INTO theses SET `id` = '8', `pdf` = '', `video` = '', `title` = 'test', `abstract` = 'wqeqweqwe', `published_at` = '2222-02-22', `categories` = '[3,2]', `keywords` = '[2,3]', `authors` = '[3,2,1]', `deleted_at` = NULL, `created_at` = '2024-03-20 06:49:41', `updated_at` = '2024-03-25 00:34:31';
INSERT INTO theses SET `id` = '9', `pdf` = 'pdf/3DrCiGeuClGnXLhvfHtdM9w4LdknHP87RVcWigGz.pdf', `video` = 'videos/W54XctIepspaVs81QJeEAIgMJJ8Pi4bzHADUM7J9.mp4', `title` = 'test10', `abstract` = 'qweqweqwe', `published_at` = '2000-10-22', `categories` = '[3,2]', `keywords` = '[3,2]', `authors` = '[1]', `deleted_at` = NULL, `created_at` = '2024-03-25 00:48:51', `updated_at` = '2024-03-25 00:48:51';
INSERT INTO theses SET `id` = '10', `pdf` = 'pdf/ukqOKGnfWxGPHbLjoZAEQ0qUQ9clNpHETgywiWvO.pdf', `video` = 'videos/zsTMsoD17h1f1tQszI9TcRUtThmE0AU69mpOOxSR.mp4', `title` = 'test11', `abstract` = 'qweqwe', `published_at` = '2000-10-22', `categories` = '[3]', `keywords` = '[3]', `authors` = '[3]', `deleted_at` = NULL, `created_at` = '2024-03-25 01:30:14', `updated_at` = '2024-03-25 01:30:14';
INSERT INTO theses SET `id` = '11', `pdf` = 'pdf/7H8fn6sF06fFKkMX0cD1PeDE6z9q9Hm4BB63rSUx.pdf', `video` = 'videos/3FLfWBZUkYBWfQyfN0YqNAMxAd0kbZ2GZ7YjJC2j.mp4', `title` = 'teset12', `abstract` = 'qwe', `published_at` = '2014-10-22', `categories` = '[2]', `keywords` = '[3]', `authors` = '[3]', `deleted_at` = NULL, `created_at` = '2024-03-25 01:33:18', `updated_at` = '2024-03-25 01:33:18';
INSERT INTO theses SET `id` = '12', `pdf` = 'pdf/oL3S2z7uMWN9TF6K784UcCBz9x6xyLG2aV7UNjEH.pdf', `video` = 'videos/iLPnTL8Dbmvdr2pg6R1v9uH51dGN6M36cP6IfHZ2.mp4', `title` = 'test13', `abstract` = 'qwe', `published_at` = '2000-02-21', `categories` = '[3]', `keywords` = '[3,2]', `authors` = '[2]', `deleted_at` = NULL, `created_at` = '2024-03-25 01:44:10', `updated_at` = '2024-03-25 01:44:10';
INSERT INTO theses SET `id` = '13', `pdf` = 'pdf/HNaX0gQupfzzb1E5gAjTe7vvCvnunaleq01L3AIj.pdf', `video` = 'videos/cLCSVtywVFSWKvCNIUGMYAs9PNoZBowNQ64dD7Pg.mp4', `title` = 'test14', `abstract` = 'qweqwe', `published_at` = '2000-11-11', `categories` = '[2]', `keywords` = '[3,2]', `authors` = '[2,3]', `deleted_at` = NULL, `created_at` = '2024-03-25 01:49:10', `updated_at` = '2024-03-25 02:10:38';
INSERT INTO theses SET `id` = '14', `pdf` = 'pdf/KaXGf1F1nvpuOxbW5BbVtDjrxgL0oFvMRgIpWuxu.pdf', `video` = 'videos/FBJpl4VOXTRxxQSlIBd3Ly3YStLsG3zIv3koa5rc.mp4', `title` = 'test15', `abstract` = 'qweqweqwewqeqwe', `published_at` = '2024-03-25', `categories` = '[2]', `keywords` = '[3,2]', `authors` = '[3]', `deleted_at` = NULL, `created_at` = '2024-03-25 04:36:21', `updated_at` = '2024-03-25 04:36:21';
INSERT INTO theses SET `id` = '15', `pdf` = 'pdf/q0XZpSawgjuucBTEhCCwsY05e2kcdWTFZM5dXlTn.pdf', `video` = 'videos/7M5ioRsuz85cdtZkHxmQVZnSDYWqLlL1k5a9IRXo.mp4', `title` = 'thesis17', `abstract` = 'qweqweqwe', `published_at` = '2024-03-25', `categories` = '[2]', `keywords` = '[3,2]', `authors` = '[3,2]', `deleted_at` = NULL, `created_at` = '2024-03-25 04:37:21', `updated_at` = '2024-03-25 04:37:21';
INSERT INTO theses SET `id` = '16', `pdf` = '', `video` = '', `title` = 'No VIDEO/PDF thesis', `abstract` = 'test', `published_at` = '2024-04-01', `categories` = '[2]', `keywords` = '[3]', `authors` = '[3]', `deleted_at` = NULL, `created_at` = '2024-04-01 02:35:13', `updated_at` = '2024-04-01 02:35:13';
INSERT INTO users SET `id` = '1', `userID` = '123', `user_type` = '3', `email` = '', `password` = '$2y$12$3XEySvsN6l3LJstbztv.IO2f5WdGImgF9wiEs93MnbrASzjOI9paW', `deleted_at` = NULL, `created_at` = '2024-03-20 00:51:32', `updated_at` = '2024-03-20 00:51:32';
INSERT INTO users SET `id` = '2', `userID` = '0200161322', `user_type` = '0', `email` = 'dayandayangenepaolo@gmail.com', `password` = '$2y$12$kdpMhTPIJ/gdCKWnbfKWr.42OmmvFyO906yOkWUM/jm.Ue6ntFSGu', `deleted_at` = NULL, `created_at` = '2024-03-25 04:22:44', `updated_at` = '2024-03-25 07:09:32';