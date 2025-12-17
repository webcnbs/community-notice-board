-- seed.sql
USE cnbs;

INSERT INTO users (username, email, password, role, status)
VALUES
('admin', 'admin@cnbs.local', '$2y$10$3IYlWJZtI5JpUe7YJ6S1ku8IYwVQfUj8jHcB7y9L3kJwVZcI1P8kq', 'admin', 'active');

INSERT INTO categories (name, description, color_code)
VALUES
('Events', 'Community events and activities', '#2E86C1'),
('Emergencies', 'Urgent notices', '#C0392B'),
('Maintenance', 'Scheduled maintenance updates', '#27AE60'),
('General', 'General information', '#7D3C98');

INSERT INTO notices (title, content, category_id, priority, user_id, expiry_date)
VALUES
('Community Cleanup Day', 'Join us this Saturday for cleanup.', 1, 'Medium', 1, DATE_ADD(CURDATE(), INTERVAL 10 DAY)),
('Water Supply Disruption', 'Emergency maintenance tonight.', 2, 'High', 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY));