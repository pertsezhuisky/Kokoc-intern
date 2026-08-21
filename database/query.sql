SELECT * FROM users
WHERE register_date >= '2026-08-05';

SELECT * FROM tasks
WHERE user_id = 2
ORDER BY created_at DESC;

SELECT u.id, u.name, COUNT(t.id) FROM users u
LEFT JOIN tasks t ON u.id = t.user_id
GROUP BY u.id, u.name;