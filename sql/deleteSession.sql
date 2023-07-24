SET SQL_SAFE_UPDATES = 0;
USE task_manager;

-- Defina o ID da sessão que você deseja excluir
SET @session_id_to_delete = 1;

-- Começar a transação
START TRANSACTION;

-- Atualize a coluna "user_sessions_id" para NULL na tabela "users" para todas as referências à sessão que você deseja excluir
UPDATE users SET user_sessions_id = NULL WHERE user_sessions_id = @session_id_to_delete;

-- Exclua a sessão na tabela "sessions" com a opção ON DELETE CASCADE, o que também remove as referências dependentes da tabela "users"
DELETE FROM sessions WHERE sessions_id = @session_id_to_delete;

-- Comitar as alterações na transação
COMMIT;

-- Não se esqueça de definir o SQL_SAFE_UPDATES de volta para 1 após as operações
SET SQL_SAFE_UPDATES = 1;