INSERT INTO users (
    name,
    email,
    password,
    phone_number,
    register_date,
    is_active
)
VALUES
    (
        'alex_dev',
        'alex_dev@yandex.ru',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llCzYz7GxX5w6H7J8K9L0',
        '+79161234567',
        '2026-08-01',
        TRUE
    ),
    (
        'john_doe',
        'john.doe@mail.ru',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llCzYz7GxX5w6H7J8K9L0',
        '+79261234568',
        '2026-08-02',
        TRUE
    ),
    (
        'maria_dev',
        'maria_dev@yandex.ru',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llCzYz7GxX5w6H7J8K9L0',
        '+79371234569',
        '2026-08-03',
        TRUE
    ),
    (
        'max_power',
        'max_power@mail.ru',
        '$2y$10$92IXUNpkj0rO0Q5byMi.Ye4oKoEa3Ro9llCzYz7GxX5w6H7J8K9L0',
        '+79031234570',
        '2026-08-04',
        FALSE
    ),
    (
        'anna_code',
        'anna.code@gmail.com',
        '$2y$10$92IXUNpkj0rO0Q5byMi.Ye4oKoEa3Ro9llCzYz7GxX5w6H7J8K9L0',
        '+79161234571',
        '2026-08-05',
        TRUE
    ),
    (
        'peter_dev',
        'peter_dev@yandex.ru',
        '$2y$10$92IXUNpkj0rO0Q5byMi.Ye4oKoEa3Ro9llCzYz7GxX5w6H7J8K9L0',
        '+79261234572',
        '2026-08-06',
        TRUE
    ),
    (
        'kate_test',
        'kate_test@mail.ru',
        '$2y$10$92IXUNpkj0rO0Q5byMi.Ye4oKoEa3Ro9llCzYz7GxX5w6H7J8K9L0',
        '+79371234573',
        '2026-08-07',
        FALSE
    ),
    (
        'tom_admin',
        'tom_admin@yandex.ru',
        '$2y$10$92IXUNpkj0rO0Q5byMi.Ye4oKoEa3Ro9llCzYz7GxX5w6H7J8K9L0',
        '+79031234574',
        '2026-08-08',
        TRUE
    );


INSERT INTO tasks (
    title,
    description,
    user_id
)
VALUES
    (
        'Implement authentication',
        'Implement user authentication and password verification',
        1
    ),
    (
        'Create database schema',
        'Create MySQL tables for users and tasks',
        1
    ),
    (
        'Write API documentation',
        'Document available API endpoints and request parameters',
        2
    ),
    (
        'Fix Docker configuration',
        'Configure PHP and MySQL containers for local development',
        2
    ),
    (
        'Add unit tests',
        'Add tests for controllers and repositories',
        3
    ),
    (
        'Implement user endpoint',
        'Implement the user registration API endpoint',
        5
    ),
    (
        'Review pull request',
        'Review the latest changes and leave feedback',
        5
    ),
    (
        'Update README',
        'Update project setup and API usage instructions',
        8
    );