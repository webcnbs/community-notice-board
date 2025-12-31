USE cnbs;

INSERT INTO notices (title, content, category_id, priority, user_id, expiry_date)
VALUES
-- Events
('Community Cleanup Day', 'Join us this Saturday for cleanup.', 1, 'Medium', 1, DATE_ADD(CURDATE(), INTERVAL 10 DAY)),
('Health Awareness Talk', 'Free health screening and talk at the community hall.', 1, 'Low', 1, DATE_ADD(CURDATE(), INTERVAL 15 DAY)),
('Sports Day Registration', 'Register now for the annual sports day.', 1, 'Medium', 1, DATE_ADD(CURDATE(), INTERVAL 20 DAY)),

-- Emergencies
('Water Supply Disruption', 'Emergency maintenance tonight.', 2, 'High', 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY)),
('Gas Leak Reported', 'Residents advised to evacuate Block B immediately.', 2, 'High', 1, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
('Flood Warning', 'Heavy rain expected. Please stay alert.', 2, 'High', 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY)),

-- Maintenance
('Elevator Maintenance', 'Elevator will be unavailable from 9AM to 5PM.', 3, 'Medium', 1, DATE_ADD(CURDATE(), INTERVAL 5 DAY)),
('Internet Service Downtime', 'Internet will be temporarily unavailable.', 3, 'Low', 1, DATE_ADD(CURDATE(), INTERVAL 7 DAY)),

-- General
('Office Closed on Public Holiday', 'Office will be closed next Monday.', 4, 'Low', 1, DATE_ADD(CURDATE(), INTERVAL 12 DAY)),
('New Parking Rules', 'Please follow updated parking guidelines.', 4, 'Medium', 1, DATE_ADD(CURDATE(), INTERVAL 30 DAY)),

-- Testing
('Old Announcement', 'This notice is expired.', 4, 'Low', 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY));

INSERT INTO comments (notice_id, user_id, content, status)
VALUES
-- Comments for Notice 1: Community Cleanup Day
(1, 2, 'I will be joining with my family.', 'approved'),
(1, 3, 'What time does the event start?', 'pending'),

-- Notice 2: Health Awareness Talk
(2, 1, 'Is registration required?', 'pending'),

-- Notice 3: Sports Day Registration
(3, 2, 'Can teenagers participate?', 'approved'),
(3, 3, 'Looking forward to this event!', 'approved'),

-- Notice 4: Water Supply Disruption
(4, 2, 'Will water be restored by morning?', 'approved'),
(4, 3, 'Thanks for the early notice.', 'approved'),

-- Notice 5: Gas Leak Reported
(5, 2, 'Emergency services are already on site.', 'approved'),
(5, 3, 'Hope everyone is safe.', 'approved'),

-- Notice 6: Flood Warning
(6, 1, 'Please avoid unnecessary travel.', 'approved'),

-- Notice 7: Elevator Maintenance
(7, 3, 'Will the stairs be accessible?', 'pending'),

-- Notice 8: Internet Service Downtime
(8, 2, 'Thanks for informing us early.', 'approved'),

-- Notice 9: Office Closed on Public Holiday
(9, 3, 'Does this include customer support?', 'pending'),

-- Notice 10: New Parking Rules
(10, 2, 'Where can we find the full guidelines?', 'approved'),

-- Expired Notice (if you added one)
(11, 1, 'This announcement is no longer relevant.', 'rejected');