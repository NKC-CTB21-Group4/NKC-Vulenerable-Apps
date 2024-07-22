UPDATE users SET
    is_admin = 1,
    registered_at = '2023-02-15 12:30:00',
    deleted_at = NULL
WHERE email = 'jane.smith@example.com';

UPDATE users SET
    is_admin = 1,
    registered_at = '2023-03-10 08:45:00',
    deleted_at = NULL
WHERE email = 'admin.user@example.com';


INSERT INTO posts (author_id, content, imagePath, created_at, deleted_at) VALUES
(1, 'This is the first post. It contains some interesting content about various topics.', NULL, '2023-07-01 10:00:00', NULL),
(2, 'Another post by a different author. This one includes an image and some more text.', NULL, '2023-07-02 11:30:00', NULL),
(3, 'This post is about technology and its impact on our daily lives. No image here.', NULL, '2023-07-03 14:45:00', NULL),
(1, 'Here is a follow-up post with more details on the previous topics. Includes a new image.', NULL, '2023-07-04 16:00:00', NULL),
(4, 'A brief post discussing recent events in the world. Short and to the point.', NULL, '2023-07-05 09:20:00', NULL),
(2, 'This post includes a discussion on the best practices for coding in Python. Image attached.', NULL, '2023-07-06 18:10:00', NULL),
(3, 'A detailed analysis of the latest trends in web development. Comprehensive and insightful.', NULL, '2023-07-07 12:00:00', '2023-07-10 12:00:00'),
(4, 'An update on the ongoing project with some interesting findings. No image.', NULL, '2023-07-08 15:30:00', NULL),
(1, 'A concluding post summarizing the series of posts. Includes final thoughts and image.', NULL, '2023-07-09 08:00:00', NULL);


INSERT INTO reactions (user_id, post_id, is_fav) VALUES
(1, 1, true),
(2, 1, true),
(3, 2, true),
(1, 3, true),
(4, 2, true),
(3, 5, true),
(3, 8, true),
(1, 9, true),
(4, 1, true),
(2, 5, true),
(3, 6, true),
(1, 7, true),
(4, 8, true),
(2, 9, true),
(3, 4, true),
(1, 2, true),
(4, 6, true),
(3, 3, true),
(1, 4, true),
(4, 7, true),
(2, 8, true),
(3, 9, true),
(1, 5, true),
(2, 3, true),
(3, 7, true),
(1, 8, true),
(4, 9, true),
(2, 4, true),
(3, 1, true),
(1, 6, true),
(4, 5, true);

INSERT INTO direct_messages (sender_id, receiver_id, message, sentAt, deleted_at) VALUES
(1, 2, 'Hey, how are you doing?', '2024-07-01 09:00:00', NULL),
(2, 1, 'I am good, thanks! How about you?', '2024-07-01 09:05:00', NULL),
(3, 4, 'Can we discuss the project updates?', '2024-07-02 10:00:00', NULL),
(4, 3, 'Sure, when are you available?', '2024-07-02 10:15:00', NULL),
(1, 3, 'Don\'t forget the meeting tomorrow.', '2024-07-03 11:00:00', NULL),
(3, 1, 'Got it! See you at the meeting.', '2024-07-03 11:10:00', NULL),
(2, 4, 'Did you receive my last message?', '2024-07-04 12:00:00', '2024-07-05 12:00:00'),
(4, 2, 'Yes, I did. Let\'s catch up soon.', '2024-07-04 12:05:00', NULL),
(2, 3, 'Can you send me the updated report?', '2024-07-05 13:00:00', NULL),
(3, 2, 'I will send it to you by the end of the day.', '2024-07-05 13:10:00', NULL);
