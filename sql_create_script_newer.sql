/*Ethan Belote*/
DROP DATABASE IF EXISTS alumniconnectdb;
CREATE DATABASE alumniconnectdb;

CREATE TABLE IF NOT EXISTS alumniconnectdb.user (
  userid INT AUTO_INCREMENT,
  role ENUM('student', 'alumni', 'faculty', 'admin'),
  name VARCHAR(45) NULL,
  email VARCHAR(45) UNIQUE NOT NULL,
  graduationyear YEAR NULL,
  major VARCHAR(45) NULL,
  aboutme LONGTEXT NULL,
  password VARCHAR(255) NOT NULL,
  email_verified TINYINT(1) DEFAULT 0,
  verification_token VARCHAR(255) NULL,
  password_reset_token VARCHAR(255) NULL,
  reset_expires_at DATETIME NULL,
  PRIMARY KEY (userid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.job (
  jobid INT NOT NULL AUTO_INCREMENT,
  title VARCHAR(45) NULL,
  description LONGTEXT NULL,
  opendate DATE NULL,
  closedate DATE NULL,
  contactemail VARCHAR(45) NOT NULL,
  alumniid INT NOT NULL,
  PRIMARY KEY (jobid),
  FOREIGN KEY (alumniid) REFERENCES alumniconnectdb.user (userid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.event (
  eventid INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NULL,
  location VARCHAR(45) NULL,
  datetime DATETIME NULL,
  creatorid INT NOT NULL,
  description LONGTEXT NULL,
  PRIMARY KEY (eventid),
  FOREIGN KEY (creatorid) REFERENCES alumniconnectdb.user (userid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.company (
  companyid INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NULL,
  description LONGTEXT NULL,
  activelistings INT NULL,
  creatorid INT NOT NULL,
  PRIMARY KEY (companyid),
  FOREIGN KEY (creatorid) REFERENCES alumniconnectdb.user (userid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.message (
  messageid INT NOT NULL AUTO_INCREMENT,
  datetime DATETIME NULL,
  contents LONGTEXT NULL,
  senderid INT NULL,
  receiverid INT NULL,
  PRIMARY KEY (messageid),
  FOREIGN KEY (senderid) REFERENCES alumniconnectdb.user (userid),
  FOREIGN KEY (receiverid) REFERENCES alumniconnectdb.user (userid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.application (
  studentid INT NOT NULL,
  jobid INT NOT NULL,
  datecreated DATE NULL,
  resume BLOB NULL,
  PRIMARY KEY (studentid, jobid),
  FOREIGN KEY (studentid) REFERENCES alumniconnectdb.user (userid),
  FOREIGN KEY (jobid) REFERENCES alumniconnectdb.job (jobid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.favorite_list (
  listid INT NOT NULL AUTO_INCREMENT,
  userid INT NOT NULL,
  PRIMARY KEY (listid),
  FOREIGN KEY (userid) REFERENCES alumniconnectdb.user (userid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.favorite_company (
  companyid INT NOT NULL,
  listid INT NOT NULL,
  PRIMARY KEY (companyid, listid),
  FOREIGN KEY (companyid) REFERENCES alumniconnectdb.company (companyid),
  FOREIGN KEY (listid) REFERENCES alumniconnectdb.favorite_list (listid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.favorite_job (
  jobid INT NOT NULL,
  listid INT NOT NULL,
  PRIMARY KEY (jobid, listid),
  FOREIGN KEY (jobid) REFERENCES alumniconnectdb.job (jobid),
  FOREIGN KEY (listid) REFERENCES alumniconnectdb.favorite_list (listid)
);

CREATE TABLE IF NOT EXISTS alumniconnectdb.favorite_event (
  eventid INT NOT NULL,
  listid INT NOT NULL,
  PRIMARY KEY (eventid, listid),
  FOREIGN KEY (eventid) REFERENCES alumniconnectdb.event (eventid),
  FOREIGN KEY (listid) REFERENCES alumniconnectdb.favorite_list (listid)
);