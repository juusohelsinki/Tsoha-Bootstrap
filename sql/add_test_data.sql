-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO user_account (user_accountid, username, email, password, usertype, name, description) VALUES (DEFAULT, 'john@usa.com', 'john@usa.com', 'Kalle123', '0', 'John Doe', 'John on länkkäri.');
INSERT INTO band (bandid, bandname, description, bandimagepath) VALUES (DEFAULT, 'John Doe orchestra', 'Rujoa bläkkistä', 'http://www.kgkgkkg.fi/kuvatus.jpg');
INSERT INTO userband (user_accountID, bandID) VALUES (1, 1);
INSERT INTO review (user_accountID, bandID, review, stars) VALUES (1, 1, 'Ei nyt oikein lähde.', 1);
INSERT INTO user_account (user_accountid, username, email, password, usertype, name, description) VALUES (DEFAULT, 'keijo@usa.com', 'keijo@usa.com', 'Kalle123', '0', 'Keijo', 'Keijo on länkkäri myöskin.');
INSERT INTO band (bandid, bandname, description, bandimagepath) VALUES (DEFAULT, 'Ranuan Rollarit', 'Gansta rap', 'http://www.kgkgkkg.fi/kuvatus.jpg');
INSERT INTO userband (user_accountID, bandID) VALUES (2, 2);
INSERT INTO review (user_accountID, bandID, review, stars) VALUES (2, 2, 'Tää on tosi hyvä!.', 5);
