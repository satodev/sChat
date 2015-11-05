CONSTRUCT System 
===============================================================================

Session {group[user] <-> message <-> group[user]}

what is session ? 
A place where a bunch of users can send message together, it can be a lot of sessions.

what is message ? 
A sending unit that can contain a lot of type of media : text mainly, but also images , sound, link and other...

what is user ? 
An loged entity that have personnal informations.


ARCHITECTURE
===============================================================================
	- Main{
		class Register{}
		class User{}
		class Group{}
		class Message{}
		class Session{}
		class Init{}
		class Interface{}
	}


TODOLIST
===============================================================================
 [ ] User
 [ ] Group
 [ ] Message
 [ ] Session

RANDOM ALGO
===============================================================================
user doesn't exists -> register -> user exists
when init dB ? user && group making || rooms && chat && messages 

DATABASE OVERVIEW
===============================================================================
table -> user : columns: id, nickname, name, password (md5 and shit), email, ip_address
table -> rooms : id, id_group, id_user, nb_user, session_activity
table -> chat : data, msg_content, user_id, room_id, group_id
table -> messages : id, id_chat, content, date
table -> group : id, id_user, group_size

(group > user) <link> (rooms > chat > messages)
