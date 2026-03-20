# WShop API

API REST de gestion de magasins développée en PHP natif dans le cadre d’un test technique.

Fonctionnalités :

- Authentification via JWT
- CRUD complet sur les magasins
- Filtrage et tri
- Validation des données
- Logs d’erreurs
- Tests unitaires et fonctionnels

Architecture :

- DDD simplifié
- SOLID
- DTO / Services / Repository

## Prérequis

- PHP >= 8.2
- Composer

## Installation

> composer install \
> php scripts/init_db.php \
> php -S localhost:8000 router.php

---

## Utilisation

### Login

> POST /login

```json
{
  "email": "admin@example.com",
  "password": "password123"
}
```

Réponse

```json
{
  "data": {
    "token": "..."
  }
}
```

Toutes les routes /stores nécessitent un token :

> Authorization: Bearer \<token\>


---

## Endpoints

### GET /stores

Liste des magasins

Filtres :

- city
- name
- postal_code

Tri :

- sort=name
- order=ASC|DESC

> GET /stores?city=Nantes&sort=name&order=DESC

```json
{
  "data": [
    {
      "id": 8,
      "name": "Nantes Centre",
      "manager_name": "Laura Petit",
      "phone": "0405060708",
      "street": "12 rue Crébillon",
      "postal_code": "44000",
      "city": "Nantes",
      "created_at": "2026-03-19T14:49:48+00:00",
      "updated_at": "2026-03-19T14:49:48+00:00"
    },
    {
      "id": 9,
      "name": "Nantes",
      "manager_name": "Jean René",
      "phone": "0405060708",
      "street": "20 rue Crébillon",
      "postal_code": "44000",
      "city": "Nantes",
      "created_at": "2026-03-19T14:52:28+00:00",
      "updated_at": "2026-03-19T14:52:28+00:00"
    }
  ],
  ...
}
```

### GET /stores/{id}

Affiche les informations d'un store

> GET /stores/1

```json
{
  "data": {
    "id": 1,
    "name": "Central Paris",
    "manager_name": "Alice Martin",
    "phone": "0102030405",
    "street": "10 rue de Rivoli",
    "postal_code": "75001",
    "city": "Paris",
    "created_at": "2026-03-19T10:00:00+00:00",
    "updated_at": "2026-03-19T10:00:00+00:00"
  }
}

```

### POST /stores

Création d'un store.

Payload

```json
{
  "name": "Nantes Centre",
  "manager_name": "Laura Petit",
  "phone": "0405060708",
  "street": "12 rue Crébillon",
  "postal_code": "44000",
  "city": "Nantes"
}
```

Réponse

```json
{
  "data": {
    "id": 8,
    "name": "Nantes Centre",
    "manager_name": "Laura Petit",
    "phone": "0405060708",
    "street": "12 rue Crébillon",
    "postal_code": "44000",
    "city": "Nantes",
    "created_at": "2026-03-19T14:49:48+00:00",
    "updated_at": "2026-03-19T14:49:48+00:00"
  }
}
```

### PUT /stores/{id}

Mets à jour le store avec l'id {$id}

Payload

```json
{
  "name": "Nantes Centre",
  "manager_name": "Laura Petit",
  "phone": "0405060708",
  "street": "12 rue Crébillon",
  "postal_code": "44000",
  "city": "Nantes"
}
```

Réponse

```json
{
  "data": {
    "id": 8,
    "name": "Nantes Centre",
    "manager_name": "Laura Petit",
    "phone": "0405060708",
    "street": "12 rue Crébillon",
    "postal_code": "44000",
    "city": "Nantes",
    "created_at": "2026-03-19T14:49:48+00:00",
    "updated_at": "2026-03-19T14:49:48+00:00"
  }
}
```

### DELETE /stores/{id}

Supprime un store existant

> DELETE /stores/1

```json
{
  "data": null
}
```

---

## Utilisateur de test

email : admin@example.com  
password : password123


---

## JWT

Le secret JWT est défini dans le code pour les besoins du test.

---

## Tests

Lancer les tests :

1. Initialiser la base :

> php scripts/init_db.php

2. Lancer le serveur :

> php -S localhost:8000 router.php

3. Lancer les tests :

> vendor/bin/phpunit

Tests inclus :

unitaires (validator, JWT, services)

fonctionnels (login, auth, CRUD)


---

## Architecture

- Domain : entités métier
- Application : services + DTO + validation
- Infrastructure : HTTP / PDO / JWT / logging
- Shared : exceptions

Principes utilisés :

- SOLID
- séparation des responsabilités
- injection de dépendances
- repository pattern

---

## Choix techniques

- SQLite pour simplifier l’évaluation et garantir la portabilité
- JWT stateless pour l’authentification
- Repository pour découpler la base de données
- DTO pour isoler les entrées/sorties
- Validation centralisée
- Logger simple pour traçabilité

---

## Améliorations possibles

- Refresh token
- Gestion des rôles / permissions
- Pagination des résultats
- Middleware pipeline plus avancé
- Dockerisation
- CI/CD

---

## Postman

Une collection Postman est fournie dans le projet.