DROP VIEW SIGGROUPS_VIEW;
DROP VIEW SIGGROUPS_USERS_VIEW;
DROP VIEW SIGGROUPS_EDIT_VIEW;
DROP VIEW COMPANIES_VIEW;
DROP VIEW EVENTS_VIEW;
DROP VIEW USERS_VIEW;
DROP VIEW USERS_BALANCES_VIEW;
DROP TRIGGER IF EXISTS siggroups_edit_view_dml_trig ON SIGGROUPS_EDIT_VIEW;
DROP TRIGGER IF EXISTS companies_edit_view_dml_trig ON COMPANIES_VIEW;

CREATE OR REPLACE VIEW SIGGROUPS_VIEW AS
SELECT SIGGROUPS.GID, TITLE AS GROUP_NAME, DESCRIPTION, (USERS.FIRSTNAME || ' ' || USERS.LASTNAME) AS LEADER, MEETING_DAY, MEETING_TIME
FROM SIGGROUPS LEFT OUTER JOIN ORGANIZERS ON (SIGGROUPS.OID = ORGANIZERS.OID)
     	       LEFT OUTER JOIN USERS ON (SIGGROUPS.LEADER_ID = USERS.UID)
ORDER BY SIGGROUPS.GID; 

CREATE OR REPLACE VIEW SIGGROUPS_EDIT_VIEW AS
SELECT SIGGROUPS.GID, TITLE, DESCRIPTION, LEADER_ID AS LEADER, MEETING_DAY, MEETING_TIME
FROM SIGGROUPS LEFT OUTER JOIN ORGANIZERS ON (SIGGROUPS.OID = ORGANIZERS.OID)
ORDER BY SIGGROUPS.GID; 

CREATE OR REPLACE VIEW SIGGROUPS_USERS_VIEW AS
SELECT G.GID, TITLE, (FIRSTNAME || ' ' || LASTNAME) AS MEMBER, UG.UID AS MEMBER_ID
FROM SIGGROUPS AS G LEFT OUTER JOIN ORGANIZERS AS O ON (G.OID = O.OID)
     	       	    LEFT OUTER JOIN USERS_SIGGROUPS AS UG ON G.GID = UG.GID
		    LEFT OUTER JOIN USERS AS U ON UG.UID = U.UID
ORDER BY UG.JOINEDDATE NULLS LAST;

CREATE OR REPLACE VIEW USERS_BALANCES_VIEW AS
SELECT U.UID, (U.FIRSTNAME || ' ' || U.LASTNAME) AS NAME, (SUM(T.AMOUNT)) AS BALANCE
FROM USERS AS U LEFT OUTER JOIN TRANSACTIONS AS T ON (U.UID = T.UID)
GROUP BY U.UID
ORDER BY U.UID;

CREATE OR REPLACE VIEW COMPANIES_VIEW AS
SELECT C.CID, TITLE AS COMPANY_NAME, CONTACT_PERSON, CONTACT_PHONE, CONTACT_EMAIL
FROM COMPANIES AS C LEFT OUTER JOIN ORGANIZERS AS O ON (C.OID = O.OID)
ORDER BY C.CID; 

CREATE OR REPLACE VIEW EVENTS_VIEW AS
SELECT E.EID, E.EVENT_NAME, E.LOCATION, E.EVENT_DATETIME, O.TITLE AS ORGANIZER
FROM EVENTS AS E LEFT OUTER JOIN ORGANIZERS AS O ON (E.OID = O.OID)
ORDER BY E.EVENT_DATETIME DESC NULLS FIRST;

CREATE OR REPLACE VIEW USERS_VIEW AS
SELECT u.uid, (firstname || ' ' || lastname) AS NAME, email, phone, username, (SUM(T.AMOUNT)) AS BALANCE, accountCreated, accountExpires, userlevel
FROM USERS AS U LEFT OUTER JOIN TRANSACTIONS AS T ON (U.UID = T.UID)
GROUP BY U.UID
ORDER BY U.UID;

/** Triggers and Functions **/


CREATE TRIGGER siggroups_edit_view_dml_trig
       INSTEAD OF INSERT OR UPDATE OR DELETE ON
       	       SIGGROUPS_EDIT_VIEW FOR EACH ROW EXECUTE PROCEDURE SIGGROUPS_EDIT_VIEW_DML();

CREATE TRIGGER companies_view_dml_trig
       INSTEAD OF INSERT OR UPDATE OR DELETE ON
       	       COMPANIES_VIEW FOR EACH ROW EXECUTE PROCEDURE COMPANIES_VIEW_DML();


CREATE OR REPLACE FUNCTION SIGGROUPS_EDIT_VIEW_DML()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $function$
   DECLARE
	new_oid	integer;
	old_oid integer;
	new_gid integer;
   BEGIN
IF TG_OP = 'INSERT' THEN
   INSERT INTO ORGANIZERS (TITLE) VALUES(NEW.title) RETURNING OID INTO new_oid;
   INSERT INTO SIGGROUPS (DESCRIPTION, LEADER_ID, MEETING_DAY, MEETING_TIME, OID) VALUES(NEW.description,NEW.leader,NEW.meeting_day, NEW.meeting_time, new_oid) RETURNING GID INTO new_gid;
   INSERT INTO USERS_SIGGROUPS (UID, GID) VALUES (NEW.leader, new_gid);
   RETURN NEW;
ELSIF TG_OP = 'UPDATE' THEN
   UPDATE SIGGROUPS SET DESCRIPTION=NEW.description,LEADER_ID=NEW.leader,MEETING_TIME=NEW.meeting_time,MEETING_DAY=NEW.meeting_day WHERE gid=OLD.gid RETURNING OID into old_oid;
   UPDATE ORGANIZERS SET TITLE=NEW.title WHERE oid=old_oid;
   RETURN NEW;
ELSIF TG_OP = 'DELETE' THEN
      DELETE FROM SIGGROUPS WHERE gid=OLD.gid RETURNING oid into old_oid;
      DELETE FROM ORGANIZERS WHERE oid=old_oid;
      RETURN NULL;
END IF;
    RETURN NEW;
END;
$function$;

CREATE OR REPLACE FUNCTION COMPANIES_VIEW_DML()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $function$
   DECLARE
	new_oid	integer;
	old_oid integer;
   BEGIN
IF TG_OP = 'INSERT' THEN
   INSERT INTO ORGANIZERS (TITLE) VALUES(NEW.company_name) RETURNING OID INTO new_oid;
   INSERT INTO COMPANIES (CONTACT_PERSON, CONTACT_PHONE, CONTACT_EMAIL, OID) VALUES(NEW.contact_person,NEW.contact_phone, NEW.contact_email, new_oid);
   RETURN NEW;
ELSIF TG_OP = 'UPDATE' THEN
   UPDATE COMPANIES SET CONTACT_PERSON=NEW.contact_person, CONTACT_PHONE=NEW.contact_phone, CONTACT_EMAIL=NEW.contact_email WHERE cid = OLD.cid RETURNING OID into old_oid;
   UPDATE ORGANIZERS SET TITLE=NEW.company_name WHERE oid=old_oid;
   RETURN NEW;
ELSIF TG_OP = 'DELETE' THEN
      DELETE FROM COMPANIES WHERE cid=OLD.cid RETURNING oid into old_oid;
      DELETE FROM ORGANIZERS WHERE oid=old_oid;
      RETURN NULL;
END IF;
    RETURN NEW;
END;
$function$;