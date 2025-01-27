PROGestion - Application de Gestion Portuaire

Structure du Projet

Entités principales
Utilisateur : Gestion des utilisateurs et de leurs rôles.
Véhicule : Informations relatives aux véhicules (numéro de châssis, marque, état).
Lot : Groupement de véhicules destiné au transport.
Avarie : Suivi des dommages et des réparations.
Camion : Gestion des camions et de leurs transports.

Contrôleurs
SecurityController : Gestion des fonctionnalités d'authentification.
DashboardController : Gestion de l'interface d'administration.
CrudControllers : Gestion des entités via EasyAdmin.
Templates
templates/base.html.twig : Template principal pour l'application.
templates/security/login.html.twig : Page de connexion des utilisateurs.
templates/dashboard/index.html.twig : Tableau de bord administratif.

Rôles et Sécurité

Rôles disponibles
ROLE_ADMIN : Accès total à toutes les fonctionnalités de l'application.
ROLE_LOGISTICIEN : Accès limité aux fonctionnalités de gestion.
ROLE_USER : Accès de base aux fonctionnalités essentielles.
Routes sécurisées
Les routes de l'application sont sécurisées en fonction des rôles définis, garantissant une gestion stricte des accès.

Structure du Code
Configuration de l'administration (EasyAdmin)
EasyAdmin est utilisé pour gérer les entités principales, offrant une interface simplifiée et des outils avancés pour la gestion des données.

Fonctionnalités CRUD

Gestion des Lots
Création de lots avec génération automatique de numéro.
Ajout et attribution de véhicules à un lot.
Suivi du statut des lots (en attente, en cours, terminé).
Liaison des lots à des camions pour le transport.

Gestion des Avaries
Enregistrement précis et détaillé des dommages.
Suivi des traitements et réparations.
Gestion des dossiers d’avarie (ouvert/clôturé).
Association des avaries aux véhicules concernés.

Interface d'administration
Tableau de bord dynamique avec statistiques personnalisées.
Gestion CRUD complète pour toutes les entités grâce à EasyAdmin.
Recherche et filtres avancés pour une meilleure navigation et gestion.

Commandes Personnalisées
Gestion des Utilisateurs

Créer un administrateur
Commande :
php bin/console app:create-admin [email] [password] [nom] [prenom]

Supprimer un utilisateur
Commande :
php bin/console app:delete-user [email]

Exemple :
Pour supprimer un utilisateur ayant pour email admin@example.com :
php bin/console app:delete-user admin@example.com