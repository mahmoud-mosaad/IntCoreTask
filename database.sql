create database IntCoreTaskDB;

create table users(
	id int AUTO_INCREMENT primary key,
	name varchar(100) not null,
	email varchar(100) not null unique,
        password varchar(255) not null
    );