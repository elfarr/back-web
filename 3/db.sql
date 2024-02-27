CREATE TABLE application (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  names varchar(128) NOT NULL DEFAULT '',
  tel varchar(20) NOT NULL DEFAULT '',
  email varchar(128) NOT NULL DEFAULT '',
  dateB DATE,
  gender varchar(20) NOT NULL DEFAULT '',
  biography varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
);

CREATE TABLE language (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  pascal int(1) NOT NULL DEFAULT 0,
  c int(1) NOT NULL DEFAULT 0,
  c_plus_plus int(1) NOT NULL DEFAULT 0,
  js int(1) NOT NULL DEFAULT 0,
  php int(1) NOT NULL DEFAULT 0,
  python int(1) NOT NULL DEFAULT 0,
  java int(1) NOT NULL DEFAULT 0,
  haskel int(1) NOT NULL DEFAULT 0,
  clojure int(1) NOT NULL DEFAULT 0,
  prolog int(1) NOT NULL DEFAULT 0,
  scala int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE application_language (
  id_lang int(10) unsigned NOT NULL,
  id_app int(10) unsigned NOT NULL
);
