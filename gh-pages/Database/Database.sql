CREATE TABLE users(
	user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(256),
	password VARCHAR(256),
	first_name VARCHAR(256),
	last_name VARCHAR(256),
	nickname VARCHAR(256)
);

/

CREATE TABLE story(
	story_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	title VARCHAR(256) NOT NULL,
	main_author VARCHAR(256),
	secondary_authors JSON, 
	characters JSON,
	media_link JSON
);

/

CREATE TABLE profile(
	user_id INT(4) NOT NULL PRIMARY KEY,
	general_pres TEXT,
	family_members JSON,
	favorite_stories JSON
);

/

