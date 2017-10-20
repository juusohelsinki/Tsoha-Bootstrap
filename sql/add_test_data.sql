-- Lisää INSERT INTO lauseet tähän tiedostoon
-- Lisätään käyttäjiä.
INSERT INTO user_account (user_accountid, username, email, password, usertype, firstname, lastname, description) VALUES (DEFAULT, 'pertti.pasanen@hotmail.com', 'pertti.pasanen@hotmail.com', 'Kalle123', '0', 'Pertti', 'Pasanen', 'Pertti on länkkäri.');
INSERT INTO user_account (user_accountid, username, email, password, usertype, name, description) VALUES (DEFAULT, 'keijo@usa.com', 'keijo@usa.com', 'Kalle123', '0', 'Keijo', 'Keijo on länkkäri myöskin.');
-- Lisätään bändejä
INSERT INTO band (bandid, bandname, description, bandimagepath) VALUES (DEFAULT, 'John Doe orchestra', 'Rujoa bläkkistä', 'http://www.kgkgkkg.fi/kuvatus.jpg');
INSERT INTO band (bandid, bandname, description, bandimagepath) VALUES (DEFAULT, 'Ranuan Rollarit', 'Gansta rap', 'http://www.kgkgkkg.fi/kuvatus.jpg');
-- Lisätään käyttäjä bändin jäseneksi
INSERT INTO userband (user_accountID, bandID) VALUES (1, 1);
INSERT INTO userband (user_accountID, bandID) VALUES (2, 2);
-- Lisätään uusi arvostelu
INSERT INTO review (user_accountID, bandID, review, stars) VALUES (1, 1, 'Ei nyt oikein lähde.', 1);
INSERT INTO review (user_accountID, bandID, review, stars) VALUES (2, 2, 'Tää on tosi hyvä!.', 5);
-- Lisätään genret
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'African');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Asian');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Avant-garde');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Blues');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Caribbean');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Classical');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Comedy');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Country');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Easy listening');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Electronic');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Folk');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Gospel');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Hip hop');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Jazz');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Latin');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Pop');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'R&B and soul');
INSERT INTO genre (genreid, name) VALUES (DEFAULT, 'Rock');
