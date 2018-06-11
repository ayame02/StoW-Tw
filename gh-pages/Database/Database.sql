CREATE TABLE users(
	user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(256) NOT NULL,
	password VARCHAR(256) NOT NULL,
	first_name VARCHAR(256),
	last_name VARCHAR(256),
	nickname VARCHAR(256)
);

/

CREATE TABLE story(
	story_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(256) NOT NULL,
	story_image VARCHAR(256),
	reccomended_age INT(4)
);

/

CREATE TABLE book_authors(
	story_id INT NOT NULL,
	author_id INT NOT NULL
);

/

CREATE TABLE authors(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(256) NOT NULL,
	role VARCHAR(256) NOT NULL
);

/

CREATE TABLE book_characters(
	story_id INT NOT NULL,
	char_id INT NOT NULL
);

/

CREATE TABLE characters(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(256) NOT NULL,
	role VARCHAR(256) NOT NULL,
	personality VARCHAR(256),
	minor_description VARCHAR(256)
);

/

CREATE TABLE book_links(
	story_id INT NOT NULL,
	media_id INT NOT NULL
);

/

CREATE TABLE media_links(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	url VARCHAR(256)
);

/

CREATE TABLE profile(
	user_id INT NOT NULL PRIMARY KEY,
	general_pres TEXT,
	family_members VARCHAR(256),
	favorite_stories INT
);


/

