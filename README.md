
# 🏆 ProAthlete - E-commerce Sport

![ProAthlete Banner](https://via.placeholder.com/1200x400/000000/ffffff?text=ProAthlete+Sport+E-commerce)

## 📋 Table des Matières
- [Description](#-description)
- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies)
- [Installation](#-installation)
- [Base de Données](#-base-de-données)
- [Utilisation](#-utilisation)
- [Structure du Projet](#-structure-du-projet)
- [Sécurité](#-sécurité)
- [Dépannage](#-dépannage)
- [Évolutions](#-évolutions)
- [Licence](#-licence)

## 🎯 Description

ProAthlete est une plateforme e-commerce moderne spécialisée dans la vente d'articles de sport pour **hommes**, **femmes** et **enfants**. Le site propose une interface élégante en noir et blanc inspirée de Nike.com avec un panneau d'administration complet.

**Caractéristiques principales :**
- Design moderne noir et blanc
- Interface responsive
- Gestion admin complète
- Pas de compte utilisateur requis
- Langue : Français

## ✨ Fonctionnalités

### 👤 Côté Client
| Fonctionnalité | Description |
|----------------|-------------|
| 🏠 **Page d'accueil** | Présentation avec produits populaires |
| 📦 **Catalogue produits** | Affichage par catégories avec filtres |
| 🔍 **Détail produit** | Fiche complète avec images et description |
| 🛒 **Système de commande** | Formulaire simplifié sans création de compte |
| 📱 **Responsive Design** | Compatible mobile, tablette et desktop |
| 📄 **Page À propos** | Présentation de l'entreprise |

### 👑 Panneau d'Administration
| Fonctionnalité | Description |
|----------------|-------------|
| 🔐 **Authentification sécurisée** | Connexion admin protégée |
| 📊 **Tableau de bord** | Statistiques et vue d'ensemble |
| 🛍️ **Gestion produits** | CRUD complet (ajout/modification/suppression) |
| 🏷️ **Gestion catégories** | Ajout/suppression de catégories dynamiques |
| 📦 **Gestion commandes** | Suivi des commandes avec statuts |
| 🖼️ **Upload d'images** | Gestion des photos produits |
| 📈 **Rapports** | Ventes, stocks, commandes en attente |

## 🛠️ Technologies

### Backend
- **PHP 7.4+** - Langage serveur principal
- **MySQL 5.7+** - Base de données relationnelle
- **PDO** - Connexion sécurisée à la DB
- **Sessions PHP** - Gestion d'authentification

### Frontend
- **HTML5** - Structure sémantique
- **CSS3** - Styles modernes avec Flexbox/Grid
- **JavaScript Vanilla** - Interactivité
- **Font Awesome 6** - Icônes

### Outils
- **phpMyAdmin** - Gestion base de données
- **Git** - Contrôle de version
- **Apache/Nginx** - Serveur web

## 🚀 Installation

### Prérequis
- Serveur web (Apache/Nginx)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Extensions PHP : PDO, MySQLi, GD (pour images)

### Installation pas à pas

#### 1. Téléchargement
```bash
# Clonez ou téléchargez les fichiers
git clone https://github.com/votre-repo/proathlete.git
cd proathlete
