CREATE TABLE application (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  names varchar(128) NOT NULL DEFAULT '',
  tel varchar(20) NOT NULL DEFAULT '',
  email varchar(128) NOT NULL DEFAULT '',
  dateB DATE,
  gender varchar(20) NOT NULL DEFAULT '',
  biography varchar(300) NOT NULL DEFAULT '',
  login varchar(128) NOT NULL DEFAULT '',
  pass varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
);

CREATE TABLE application_language (
  id int(100) unsigned NOT NULL AUTO_INCREMENT,
  id_lang int(10) unsigned NOT NULL,
  id_app int(10) unsigned NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_lang) REFERENCES languages(id),
  FOREIGN KEY (id_app) REFERENCES application(id)
);