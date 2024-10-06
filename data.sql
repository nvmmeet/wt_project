-- $2y$10$.j/YLHrvKm/oEQ2GLdUp0.yZMwhJFdrpmHGii4MSw0Ru87JesjxXC

INSERT INTO `users` (`name`, `username`, `password`, `profile_pic_url`, `dob`) VALUES ('Meet Sanghvi', 'nvm_meet', '$2y$10$.j/YLHrvKm/oEQ2GLdUp0.yZMwhJFdrpmHGii4MSw0Ru87JesjxXC', 'default', '1989-12-13');

INSERT INTO `songs` (`song_name`, `user_id`, `song_pic_url`) VALUES
('Blank Space', 1, 'blank_space.jpeg'),
('Shape of You', 2, 'shape_of_you.jpeg'),
('Bad Guy', 3, 'bad_guy.jpeg'),
('Blinding Lights', 4, 'blinding_lights.jpeg'),
('Hello', 5, 'hello.jpeg'),
('Lover', 1, 'lover.jpeg'),
('Perfect', 2, 'perfect.jpeg'),
('Ocean Eyes', 3, 'ocean_eyes.jpeg'),
('Save Your Tears', 4, 'save_your_tears.jpeg'),
('Rolling in the Deep', 5, 'rolling_in_the_deep.jpeg');


INSERT INTO `playlists` (`playlist_name`, `playlist_pic_url`, `user_id`) VALUES
('Top Hits 2023', 'top_hits.jpeg', 1),
('Chill Vibes', 'chill_vibes.jpeg', 2),
('Workout Playlist', 'workout.jpeg', 3),
('Sad Songs', 'sad_songs.jpeg', 4),
('Pop Classics', 'pop_classics.jpeg', 5);


INSERT INTO `albums` (`album_name`, `album_pic_url`, `user_id`) VALUES
('1989', '1989.jpeg', 1),
('Divide', 'divide.jpeg', 2),
('When We All Fall Asleep, Where Do We Go?', 'wwafawdwg.jpeg', 3),
('After Hours', 'after_hours.jpeg', 4),
('25', '25.jpeg', 5);


INSERT INTO `social` (`followed_id`, `following_id`) VALUES
(2, 1), -- Ed Sheeran follows Taylor Swift
(3, 1), -- Billie Eilish follows Taylor Swift
(4, 2), -- The Weeknd follows Ed Sheeran
(5, 3), -- Adele follows Billie Eilish
(1, 4); -- Taylor Swift follows The Weeknd


INSERT INTO `album_songs` (`album_id`, `song_id`) VALUES
(1, 1), -- Blank Space in 1989
(1, 6), -- Lover in 1989
(2, 2), -- Shape of You in Divide
(2, 7), -- Perfect in Divide
(3, 3), -- Bad Guy in WWAFADWG
(3, 8), -- Ocean Eyes in WWAFADWG
(4, 4), -- Blinding Lights in After Hours
(4, 9), -- Save Your Tears in After Hours
(5, 5), -- Hello in 25
(5, 10); -- Rolling in the Deep in 25

INSERT INTO `playlist_songs` (`playlist_id`, `song_id`) VALUES
(1, 1), -- Blank Space in Top Hits 2023
(1, 2), -- Shape of You in Top Hits 2023
(2, 7), -- Perfect in Chill Vibes
(2, 8), -- Ocean Eyes in Chill Vibes
(3, 4), -- Blinding Lights in Workout Playlist
(3, 9), -- Save Your Tears in Workout Playlist
(4, 5), -- Hello in Sad Songs
(4, 10), -- Rolling in the Deep in Sad Songs
(5, 1), -- Blank Space in Pop Classics
(5, 2); -- Shape of You in Pop Classics


INSERT INTO `fav_songs` (`user_id`, `song_id`) VALUES
(1, 4), -- Taylor Swift likes Blinding Lights
(2, 1), -- Ed Sheeran likes Blank Space
(3, 5), -- Billie Eilish likes Hello
(4, 3), -- The Weeknd likes Bad Guy
(5, 2); -- Adele likes Shape of You
