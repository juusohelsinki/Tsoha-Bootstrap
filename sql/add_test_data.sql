-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO users (usersid, username, email, password, usertype, name, description) VALUES (0, 'john@usa.com', 'john@usa.com', 'Kalle123', '0', 'John Doe', 'John on länkkäri.');
INSERT INTO bands (bandsid, bandname, description, bandimagepath) VALUES (0, 'John Doe orchestra', 'Rujoa bläkkistä', 'http://www.kgkgkkg.fi/kuvatus.jpg');
INSERT INTO usersinbands (usersID, bandsID) VALUES (3, 4);
INSERT INTO permissions (usersID, bandsID, userlevel) VALUES (3, 4, 0);
INSERT INTO reviews (usersID, bandsID, review, stars) VALUES (3, 4, 'Ei nyt oikein lähde.', 1);