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
	characters BLOB,
	media_link VARCHAR(256),
	story_image VARCHAR(256)
);

/

CREATE TABLE book_authors(
	story_id INT(4) NOT NULL,
	author_id INT(4) NOT NULL
);

/

CREATE TABLE authors(
	id INT(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(256) NOT NULL,
	role VARCHAR(256) NOT NULL
);

/

CREATE TABLE profile(
	user_id INT(4) NOT NULL PRIMARY KEY,
	general_pres TEXT,
	family_members VARCHAR(256),
	favorite_stories INT
);


/

