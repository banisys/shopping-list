## ⚙️ Prerequisites

* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)

---

## 🚀 How to Run the Project

### 1. Start the Containers

Run the project using the following command:

```bash
docker-compose up -d --build
```

### 2. Import the Database

Import the SQL file into the MySQL service:

```bash
docker exec -i shopping_list_mysql mysql -u root --password=root < ./database/shopping_list.sql
```

### 3. Install Composer Dependencies

Install Composer packages:

```bash
docker exec -it shopping_list_php composer install
```

---

## 🌐 Access the Application

* browser: `http://localhost:8000`
* phpMyAdmin: `http://localhost:8080`

  * Server: `mysql`
  * Username: `root`
  * Password: `root`
