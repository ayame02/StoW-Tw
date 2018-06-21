drop table profile;
CREATE TABLE profile(
	user_id INT NOT NULL PRIMARY KEY,
	general_pres TEXT,
	family_members VARCHAR(256),
	favorite_stories INT
);
drop table users;
CREATE TABLE users(
	user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(256) NOT NULL,
	password VARCHAR(256) NOT NULL,
	first_name VARCHAR(256),
	last_name VARCHAR(256),
	nickname VARCHAR(256),
	uploaded_story INT
);
drop table book_bookmarks;
CREATE TABLE book_bookmarks(
	user_id INT NOT NULL,
	story_id INT NOT NULL,
	bookmark VARCHAR(256)
);
drop table story;
CREATE TABLE story(
	story_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(256) NOT NULL,
	nickname VARCHAR(256),
	story_image VARCHAR(256),
	reccomended_age INT(4),
	user VARCHAR(256),
	description VARCHAR(3000)
);
drop table book_authors;
CREATE TABLE book_authors(
	story_id INT NOT NULL,
	author_id INT NOT NULL
);
drop table authors;
CREATE TABLE authors(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(256) NOT NULL,
	role VARCHAR(256) NOT NULL
);
drop table book_characters;
CREATE TABLE book_characters(
	story_id INT NOT NULL,
	char_id INT NOT NULL
);
drop table characters;
CREATE TABLE characters(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(256) NOT NULL,
	role VARCHAR(256) NOT NULL,
	personality VARCHAR(256),
	minor_description VARCHAR(256)
);
drop table book_links;
CREATE TABLE book_links(
	story_id INT NOT NULL,
	media_id INT NOT NULL
);
drop table media_links;
CREATE TABLE media_links(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	url VARCHAR(256)
);