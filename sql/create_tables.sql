-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE user_account (
    user_accountID SERIAL PRIMARY KEY,
    username varchar(200),
    email varchar(200),
    password varchar(200),
    usertype int,
    firstname varchar(200),
    lastname varchar(200),
    description varchar(400)
);

CREATE TABLE band (
    bandID SERIAL PRIMARY KEY,
    bandname varchar(400),
    description varchar(4000),
    bandtypeid int,
    bandImagePath varchar(500),
    established integer,
    homecity varchar(400),
    country varchar(400),
    linktohomepage varchar(1000),
    twitter varchar(1000),
    facebook varchar(1000),
    myspace varchar(1000),
    youtube varchar(1000),
    soundcloud varchar(1000)
);

CREATE TABLE userband (
    user_accountID int,
    bandID int,
    FOREIGN KEY (user_accountID) REFERENCES user_account(user_accountID),
    FOREIGN KEY (bandID) REFERENCES band(bandID),
    PRIMARY KEY(user_accountid, bandid)
);

CREATE TABLE genre (
    genreID SERIAL PRIMARY KEY,
    name varchar(4000)
);

CREATE TABLE bandgenre (
    bandID int,
    genreid int,
    FOREIGN KEY (genreid) REFERENCES genre(genreid),
    FOREIGN KEY (bandID) REFERENCES band(bandID),
    PRIMARY KEY(bandid, genreid)
);

CREATE TABLE review (
    reviewID SERIAL PRIMARY KEY,
    user_accountID int,
    bandID int,
    review varchar(5000),
    stars int,
    FOREIGN KEY (user_accountID) REFERENCES user_account(user_accountID),
    FOREIGN KEY (bandID) REFERENCES band(bandID)
);

