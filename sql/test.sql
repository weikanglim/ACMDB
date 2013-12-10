INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Admin' , 'Admin' , 'admin@ndsu.edu' , 'admin' , 'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '2');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Group' , 'Leader' , 'group.leader@ndsu.edu' , 'leader' , 'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '0');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'User' , 'User' , 'test.dummy@ndsu.edu' , 'user' , 'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '0');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Josh' , 'Tan' , 'josh.tan@ndsu.edu' , 'josh.tan' , 'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=' , '2013-12-09' , '2014-12-09' , '0');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Sam' , 'Studsman' , 'sam.studsman@ndsu.edu' , 'sam.studsman' , 'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=' , '2013-12-09' , '2014-12-09' , '0');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Cesar' , 'Ramirez' , 'cesar.ramirez@ndsu.edu' , 'cesar.ramirez' , 'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=' , '2013-12-09' , '2014-12-09' , '0');


INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-Mobi' , 'Mobile development group.', 6); -- User 6: Cesar Ramirez
INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-GDev' , 'Game development group.', 5); -- User 5: Sam Studsman
INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-Security' , 'Crytography, security issues discussion group.', 4); -- User 4: Josh Tan
INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-Coin' , 'Cryptocurrency research group.', 4); -- User 4: Josh Tan

INSERT INTO COMPANIES_VIEW(company_name, contact_person, contact_phone) values ('Microsoft', 'Bill Gates', '2012548754');
INSERT INTO COMPANIES_VIEW(company_name, contact_person, contact_phone) values ('Evolution 1', 'Sarah Smith' , '7016548265');
INSERT INTO COMPANIES_VIEW(company_name, contact_person, contact_phone) values ('Hitachi' , 'James Carne' , '4569856452');


INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('Mobi Meeting', 'IACC 162', '2013-12-31 11:30', 1); -- OID 1: SIG-Mobi
INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('Microsoft Event', 'IACC 104', '2013-12-30 11:30', 5); -- OID 5: Microsoft
INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('GameDev Meeting', 'IACC 162', '2013-12-10 10:30', 2); -- OID 2: SIG-GDev
INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('SIG-Coin Meeting', 'IACC 162', '2013-12-16 14:00', 4); -- OID 4: SIG-Coin
INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('Hitachi Corporate Talk', 'IACC 104', '2013-12-20 18:00', 7); -- OID 7: Hitachi
INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('Evolution 1 Corporate Talk', 'IACC 104', '2013-12-21 17:00', 6); -- OID 6: Evolution 1

INSERT INTO USERS_SIGGROUPS (gid, uid) values (1,2);
INSERT INTO USERS_SIGGROUPS (gid, uid) values (2,2);
INSERT INTO USERS_SIGGROUPS (gid, uid) values (3,1);

INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Ramen Noodles', '2013-12-09 21:28:11' , 4); --OID 4: Josh Tan
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Mac N\' Cheese', '2013-12-10 20:48:31' , 5); --OID 4: Sam
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Mac N\' Cheese', '2013-12-11 14:57:13' , 5);
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Mac N\' Cheese', '2013-12-11 12:23:14' , 5);
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Mac N\' Cheese', '2013-12-12 10:11:51' , 5);
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Mac N\' Cheese', '2013-12-12 08:46:01' , 5);
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Mac N\' Cheese', '2013-12-13 11:27:22' , 5);
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Mac N\' Cheese', '2013-12-13 13:34:33' , 5);
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Mac N\' Cheese', '2013-12-13 12:19:44' , 5);
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Pop', '2013-12-11 11:18:11' , 6);
INSERT INTO TRANSACTIONS(amount, description, time_initiated, uid) values ('-0.50' , 'Pop', '2013-12-09 07:36:56' , 5);
