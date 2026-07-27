INSERT INTO access_levels (access_level_name, access_level_code) VALUES
('Basic', 1),
('Reviewer', 2),
('Administrator', 3),
('Chief Administrator', 4);

INSERT INTO task_status (task_status_name, task_status_code) VALUES
('Created', 1),
('Initiated', 2),
('Finished', 3);

INSERT INTO users (user_username, user_firstname, user_lastname, user_fullname, user_email, user_password_hash, user_access_level_code) VALUES
('demo_user', 'Demo', 'User', 'Demo User', 'demo@example.com', '$2y$10$wRbG8K5b4eLm64F6A.1ykeyQtTizrhLO956FJw1HKlMRI8ArsSv1q', 4)'albuquerque.lucas', 'Lucas', 'Albuquerque', 'Lucas Albuquerque', 'demo@example.com', '$argon2id$v=19$m=65536,t=4,p=1$MFgzNlJ6b1RLNW9KUUNscw$zKhCYYpvsLlLPuNkmJI2u3/e7DFfv4ChVkyIB6sg2AA', 4

INSERT INTO tasks (task_name, task_description, task_creation_date, task_init_date, task_conclusion_date, task_status_id, task_user_id)
VALUES
  ('Tarefa 1', 'Descrição da Tarefa 1', '2023-07-26 10:00:00', '---', '---', 1, 1),
  ('Tarefa 2', 'Descrição da Tarefa 2', '2023-07-27 14:00:00', '---', '---', 1, 1),
  ('Tarefa 3', 'Descrição da Tarefa 3', '2023-07-28 13:00:00', '---', '---', 1, 1);