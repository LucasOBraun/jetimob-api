# Jetimob API

API RESTful construída com Laravel para a gestão de clientes e transações financeiras. Permite o cadastro de clientes, depósitos e transferências entre contas, com autenticação baseada em API Key.

## 📦 Tecnologias Utilizadas

* **PHP 8.2**: Linguagem de programação.
* **Laravel 10.x**: Framework MVC para desenvolvimento ágil.
* **MySQL**: Banco de dados relacional.
* **Docker**: Containerização da aplicação.
* **Nginx**: Servidor web.
* **Postman**: Testes de API.

## 🚀 Instruções para Execução

### 1. Clonar o Repositório

```bash
git clone https://github.com/LucasOBraun/jetimob-api.git
cd jetimob-api
```

### 2. Configurar o Ambiente

Copie o arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

Edite o arquivo `.env` para configurar as variáveis de ambiente, como conexão com o banco de dados.

### 3. Instalar Dependências

```bash
composer install
```

### 4. Iniciar servidor com Docker 📦
A aplicação pode ser executada utilizando Docker. Certifique-se de ter o Docker e o Docker Compose instalados. Em seguida, execute:

```bash
docker compose up -d
```

Isso irá construir os containers necessários e iniciar a aplicação.
A aplicação estará disponível em `http://localhost:8000`.

### 5. Acessar o container da aplicação

```bash
docker ps 
docker exec -it container-da-aplicacao-laravel up -d
```
Nome do container de acordo com o nome que aparecer no ```docker ps```.

### 6. Gerar a Chave da Aplicação

```bash
php artisan key:generate
```

### 7. Rodar as Migrations

```bash
php artisan migrate
```

## 🔐 Autenticação

A API utiliza autenticação via chave de API. Para cada requisição, inclua o cabeçalho:

```
Auth Type: API Key;
Key: Authorization;
Value: {sua_api_key}
```

## 🧾 Endpoints Disponíveis

### Clientes

* `POST /api/clientes`: Cria um novo cliente.
* `GET /api/clientes`: Retorna os dados de todos os clientes.
* `GET /api/clientes/{id}`: Retorna os dados do cliente específico (a APIKey tem que corresponder ao id da requisição).

### Transações

* `POST /api/clientes/{id}/deposito`: Realiza um depósito na conta do cliente autenticado.
* `POST /api/clientes/{id}/transferencia`: Realiza uma transferência para o cliente com o ID especificado.

## ⚠️ Validações e Erros

A API retorna os seguintes erros com seus respectivos códigos:

* `CLIENT_NOT_FOUND`: Cliente não encontrado.
* `API_KEY_INVALID`: Chave de API inválida.
* `INSUFFICIENT_FUNDS`: Saldo insuficiente para a operação.
* `INVALID_DATA`: Dados inválidos fornecidos na requisição.
* `INVALID_DEPOSIT_VALUE`: Valor inválido para depósito.
* `INVALID_TRANSFER_VALUE`: Valor inválido para transferência.
* `MISSING_RECIPIENT`: Destinatário não informado.
* `TRANSFER_TO_SELF`: Cliente não pode transferir para sua própria conta.


## 📁 Arquivos Adicionais

- [Postman Collection](docs/jetimob.postman_collection.json) - collection para testar a API


## 📄 Licença

Este projeto está licenciado sob a MIT License - veja o arquivo [LICENSE](LICENSE) para mais detalhes.
