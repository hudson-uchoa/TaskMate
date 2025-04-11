# 📝 TaskMate

TaskMate é uma aplicação de lista de tarefas (to-do list) com autenticação de usuários, gerenciamento completo de tarefas e contagem de status (pendente/concluída).

## 🚀 Tecnologias

- ⚙️ Backend: PHP 7.4 + Slim Framework
- 🖼️ Frontend: React + Vite + Bootstrap
- 🐘 Banco de Dados: PostgreSQL
- 💾 Cache: Redis
- 🐳 Docker + Docker Compose

Repositório oficial: [https://github.com/hudson-uchoa/TaskMate.git](https://github.com/hudson-uchoa/TaskMate.git)

---

## 📦 Clonando o Projeto

```bash
git clone https://github.com/hudson-uchoa/TaskMate.git
cd TaskMate
```

---

## ⚙️ Arquivo `.env`

Antes de iniciar, crie um arquivo `.env` na raiz do projeto com as seguintes variáveis:

```env
APP_PORT=8000

DB_PORT=5432
DB_DATABASE=taskmate
DB_USERNAME=postgres
DB_PASSWORD=secret

REDIS_PORT=6379

FRONT_PORT=3000
```

---

## 🧪 Executando em Ambiente de Desenvolvimento

Esse modo usa hot reload no frontend e é recomendado para desenvolvimento.

```bash
docker-compose up -d
```

Acesse:
- Frontend (Vite): [http://localhost:5173](http://localhost:5173)
- API: [http://localhost:8000](http://localhost:8000)

---

## 🏗️ Executando em Modo Produção

Nesse modo, o frontend é buildado com Vite e servido via Nginx.

```bash
docker-compose -f docker-compose.yml up --build
```

Acesse:
- Frontend (Nginx): [http://localhost:3000](http://localhost:3000)
- API: [http://localhost:8000](http://localhost:8000)

---

## 🧼 Limpando os containers

Se quiser remover todos os containers, volumes e redes:

```bash
docker-compose down -v --remove-orphans
```

---

## 🛠️ Estrutura do Projeto

```
TaskMate/
├── taskmate-api/        # Backend em PHP + Slim
├── taskmate-web/        # Frontend em React + Vite
├── docker-compose.yml
├── docker-compose.override.yml
└── .env                 # Variáveis de ambiente
```

---

## 🤝 Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou pull requests.

---

## 🧑‍💻 Autor

**Hudson Uchoa** - [@hudson-uchoa](https://github.com/hudson-uchoa)
