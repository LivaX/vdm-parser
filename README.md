# Vdm-Parser
Retrouve les 200 derniers posts du site viedemerde.fr

## Installation

Cloner le repo Git du projet
```bash
git clone https://github.com/LivaX/vdm-parser.git
```
Récupérer la dernière release
```bash
git checkout tags/1.0.1
```

```bash
php app/console cache:clear
php app/console cache:warmup
```

```bash
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
```

```bash
php app/console doctrine:schema:drop --force
php app/console doctrine:schema:update --force
```

## 1. Retrouver la liste des posts
```bash
php app/console vdm:parse
```

## 2. Lecture des enregistrements au format JSON

 - Lancer le serveur local pour accéder à l'api
```bash
php app/console server:run
```

- Dans un navigateur , accéder aux pages:

- [Accéder à tous les posts](http://localhost:8000/api/posts)

- [Posts par auteur](http://localhost:8000/api/posts?author=Anonyme)
- [Posts par date](http://localhost:8000/api/posts?from=2016-01-01&to=2016-01-11)
- [Post par id](http://localhost:8000/api/posts/1)