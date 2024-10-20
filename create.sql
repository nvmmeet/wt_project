CREATE TABLE `users` (
    `user_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `profile_pic_url` VARCHAR(255) NOT NULL,
    `dob` DATE NOT NULL
);

CREATE TABLE `songs` (
    `song_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `song_name` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `song_pic_url` VARCHAR(255) NOT NULL,
    `song_url` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);

CREATE TABLE `playlists` (
    `playlist_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `playlist_name` VARCHAR(255) NOT NULL,
    `playlist_pic_url` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);

CREATE TABLE `albums` (
    `album_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `album_name` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `album_pic_url` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
);

CREATE TABLE `social` (
    `social_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `followed_id` BIGINT UNSIGNED NOT NULL,
    `following_id` BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (`followed_id`) REFERENCES `users`(`user_id`),
    FOREIGN KEY (`following_id`) REFERENCES `users`(`user_id`)
);

CREATE TABLE `album_songs` (
    `al_song_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `album_id` BIGINT UNSIGNED NOT NULL,
    `song_id` BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (`album_id`) REFERENCES `albums`(`album_id`),
    FOREIGN KEY (`song_id`) REFERENCES `songs`(`song_id`)
);

CREATE TABLE `playlist_songs` (
    `pl_son_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `playlist_id` BIGINT UNSIGNED NOT NULL,
    `song_id` BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (`playlist_id`) REFERENCES `playlists`(`playlist_id`),
    FOREIGN KEY (`song_id`) REFERENCES `songs`(`song_id`)
);

CREATE TABLE `fav_songs` (
    `fav_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `song_id` BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
    FOREIGN KEY (`song_id`) REFERENCES `songs`(`song_id`)
);


CREATE TABLE `admin` (
    `admin_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `admin_username` VARCHAR(50) NOT NULL UNIQUE,
    `admin_password` VARCHAR(255) NOT NULL
);

ALTER TABLE fav_songs
DROP FOREIGN KEY fav_songs_ibfk_1;

ALTER TABLE fav_songs
ADD CONSTRAINT fav_songs_ibfk_1
FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE;
