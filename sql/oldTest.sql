INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Admin-Leader' , 'Admin-Leader' , 'test.dummy@ndsu.edu' , 'admin' ,'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '2');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Group Leader' , 'Group Leader' , 'test2.dummy@ndsu.edu' , 'leader' ,'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '0');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'User' , 'User' , 'test.3dummy@ndsu.edu' , 'user' ,'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '0');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Admin' , 'Admin' , 'test.4dummy@ndsu.edu' , 'admin1' ,'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '2');


INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-Mobi', 'Mobile development group.', 1);
INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-GDev', 'Game development group.', 1);
INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-Security', 'Crytography, security issues discussion group.', 2);

INSERT INTO COMPANIES_VIEW(company_name, contact_person, contact_phone) values ('Microsoft', 'Bill Gates', '123123123');

INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('Mobi Meeting', 'IACC', '2013-12-31 11:30', 1);
INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('Microsoft Event', 'IACC', '2013-12-30 11:30', 4);
INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('GameDev Meeting', 'IACC', '2013-12-10 10:30', 2);

INSERT INTO USERS_SIGGROUPS (gid, uid) values (1,2);
INSERT INTO USERS_SIGGROUPS (gid, uid) values (2,2);
INSERT INTO USERS_SIGGROUPS (gid, uid) values (3,1);
