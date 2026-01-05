-- seed.sql
USE cnbs;

INSERT INTO users (username, email, password, role, status)
VALUES
-- password: password123
('admin', 'admin@cnbs.local', '$2y$10$wH8YxLq6U7ZQnN9V4Jc8nOZq1YxY9WQFJQX1rJwFJQxY9Y0pYFZ6', 'admin', 'active'),
('manager1', 'manager1@example.com', '$2y$10$wH8YxLq6U7ZQnN9V4Jc8nOZq1YxY9WQFJQX1rJwFJQxY9Y0pYFZ6', 'manager', 'active'),
('resident1', 'resident1@example.com', '$2y$10$wH8YxLq6U7ZQnN9V4Jc8nOZq1YxY9WQFJQX1rJwFJQxY9Y0pYFZ6', 'resident', 'active'),
('Vinod', 'vs@e.com', '$2y$10$pd0IekfUV0JFvAsZE53w/./7i.G/o7nHS7b4LMMaa9vObMlytyEQm', 'resident', 'active'),
('resident2', 'resident2@example.com', '$2y$10$wH8YxLq6U7ZQnN9V4Jc8nOZq1YxY9WQFJQX1rJwFJQxY9Y0pYFZ6', 'resident', 'pending'),
('resident3', 'resident3@example.com', '$2y$10$wH8YxLq6U7ZQnN9V4Jc8nOZq1YxY9WQFJQX1rJwFJQxY9Y0pYFZ6', 'resident', 'disabled');


INSERT INTO categories (name, description, color_code)
VALUES
('Events', 'Community events and activities', '#2E86C1'),
('Emergencies', 'Urgent notices', '#C0392B'),
('Maintenance', 'Scheduled maintenance updates', '#27AE60'),
('General', 'General information', '#7D3C98');
