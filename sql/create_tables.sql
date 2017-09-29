-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE user_account (
    user_accountID SERIAL PRIMARY KEY,
    username varchar(200),
    email varchar(200),
    password varchar(200),
    usertype int,
    name varchar(200),
    description varchar(200)
);

CREATE TABLE band (
    bandID SERIAL PRIMARY KEY,
    bandname varchar(400),
    description varchar(4000),
    bandImagePath varchar(500),
    established integer,
    homecity varchar(400),
    country varchar(400),
    genre varchar(200),
    linktohomepage varchar(1000),
    twitter varchar(1000),
    facebook varchar(1000),
    myspace varchar(1000),
    youtube varchar(1000),
    soundcloud varchar(1000)
);

/*CREATE TABLE permission (
    permissionsID SERIAL PRIMARY KEY,
    usersID int,
    bandsID int,
    userLevel int,
    FOREIGN KEY (usersID) REFERENCES users(usersID),
    FOREIGN KEY (bandsID) REFERENCES bands(bandsID)
);*/

CREATE TABLE review (
    reviewID SERIAL PRIMARY KEY,
    user_accountID int,
    bandID int,
    review varchar(5000),
    stars int,
    FOREIGN KEY (user_accountID) REFERENCES user_account(user_accountID),
    FOREIGN KEY (bandID) REFERENCES band(bandID)
);

CREATE TABLE userband (
    user_accountID int,
    bandID int,
    FOREIGN KEY (user_accountID) REFERENCES user_account(user_accountID),
    FOREIGN KEY (bandID) REFERENCES band(bandID),
    PRIMARY KEY(user_accountid, bandid)
);
