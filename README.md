# Copiar o .env.examplo e realizar configuração das variáveis de ambiente
cp .env.example .env

# Instalação e inicialização dos containers:
docker compose up -d

# Instalação do composer e suas dependências:
composer install --ignore-platform-reqs
composer dump-autoload

# Importação do dump de banco de dados via Docker:
docker exec -i mysql-container mysql -U root -d database_apache < dump_database_apache.sql


