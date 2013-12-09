INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Test' , 'Test' , 'test.dummy@ndsu.edu' , 'admin' ,'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '2');
INSERT INTO USERS(firstName, lastName, email, username, password, salt, accountCreated, accountExpires, userlevel) VALUES ( 'Moderator' , 'Moderator' , 'test.dummy@ndsu.edu' , 'moderator' ,'kIsNdRuRWnANTUBR9WKyIzvs8/dkjHK3sgAVzisDon0=', 'x/nY98Me4UyjN6e2FxsR2KtlCD2ugQpZDid6AaEVuys=', '2013-10-29' , '2014-10-29' , '0');


INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-Mobi', 'Mobile development group.', 128);
INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-GDev', 'Game development group.', 128);
INSERT INTO SIGGROUPS_EDIT_VIEW(title, description, leader) values ('SIG-Security', 'Crytography, security issues discussion group.', 2);

INSERT INTO COMPANIES_VIEW(company_name, contact_person, contact_phone) values ('DigiKey', 'Curtis Huot', '123123123');

INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('AI Competition', 'IACC', '2013-12-07 11:30', 1);
INSERT INTO EVENTS(event_name, location, event_datetime, oid) values ('DiGiKey Event', 'IACC', '2013-12-07 11:30', 4);

INSERT INTO USERS_SIGGROUPS (gid, uid) values (1,127);
INSERT INTO USERS_SIGGROUPS (gid, uid) values (3,127);
INSERT INTO USERS_SIGGROUPS (gid, uid) values (1,2);
