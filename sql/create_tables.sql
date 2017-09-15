-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE users (
    usersID SERIAL PRIMARY KEY,
    username varchar(200) ,
    email varchar(200),
    password varchar(200),
    usertype int,
    name varchar(200),
    description varchar(200)
);

CREATE TABLE bands (
    bandsID SERIAL PRIMARY KEY,
    bandName varchar(200),
    description varchar(4000),
    bandImagePath varchar(500)
);

CREATE TABLE permissions (
    permissionsID SERIAL PRIMARY KEY,
    usersID int,
    bandsID int,
    userLevel int,
    FOREIGN KEY (usersID) REFERENCES users(usersID),
    FOREIGN KEY (bandsID) REFERENCES bands(bandsID)
);

CREATE TABLE reviews (
    reviewsID SERIAL PRIMARY KEY,
    usersID int,
    bandsID int,
    review varchar(5000),
    stars int,
    FOREIGN KEY (usersID) REFERENCES users(usersID),
    FOREIGN KEY (bandsID) REFERENCES bands(bandsID)
);

CREATE TABLE usersinbands (
    usersinbandsID SERIAL PRIMARY KEY,
    usersID int,
    bandsID int,
    FOREIGN KEY (usersID) REFERENCES users(usersID),
    FOREIGN KEY (bandsID) REFERENCES bands(bandsID)
);
