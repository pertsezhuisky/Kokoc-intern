INSERT INTO users (nickname, email, phone_number, register_date, is_active)
VALUES
    ('alex_dev', 'alex_dev@yandex.ru', '+79161234567', '2026-08-01', TRUE),
    ('john_doe', 'john.doe@mail.ru', '+79261234568', '2026-08-02', TRUE),
    ('maria_dev', 'maria_dev@yandex.ru', '+79371234569', '2026-08-03', TRUE),
    ('max_power', 'max_power@mail.ru', '+79031234570', '2026-08-04', FALSE),
    ('anna_code', 'anna.code@gmail.com', '+79161234571', '2026-08-05', TRUE),
    ('peter_dev', 'peter_dev@yandex.ru', '+79261234572', '2026-08-06', TRUE),
    ('kate_test', 'kate_test@mail.ru', '+79371234573', '2026-08-07', FALSE),
    ('tom_admin', 'tom_admin@yandex.ru', '+79031234574', '2026-08-08', TRUE);

INSERT INTO tasks (name, user_id)
VALUES
    ('Implement authentication', 1),
    ('Create database schema', 1),
    ('Write API documentation', 2),
    ('Fix Docker configuration', 2),
    ('Add unit tests', 3),
    ('Implement user endpoint', 5),
    ('Review pull request', 5),
    ('Update README', 8);