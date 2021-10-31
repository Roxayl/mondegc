# Créer les bases de données (dev et test).
CREATE DATABASE IF NOT EXISTS `mondegc`;
CREATE DATABASE IF NOT EXISTS `mondegc_testing`;

# Créer l'utilisateur root et octroyer les accès.
CREATE USER 'root'@'localhost' IDENTIFIED BY 'local';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
