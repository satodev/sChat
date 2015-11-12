#CONSTRUCT System 

Session {group[user] <-> message <-> group[user]}

what is session ? 
A place where a bunch of users can send message together, it can be a lot of sessions.

what is message ? 
A sending unit that can contain a lot of type of media : text mainly, but also images , sound, link and other...

what is user ? 
An loged entity that have personnal informations.


#ARCHITECTURE

	- Main{
		class Register{}
		class User{}
		class Group{}
		class Message{}
		class Session{}
		class Init{}
		class Interface{}
	}


#TODOLIST

- [ ] User
- [ ] Group
- [ ] Message
- [ ] Session


#RANDOM ALGO && SELFASKS

user doesn't exists -> register -> user exists
when init dB ? user && group making || rooms && chat && messages 

#DATABASE OVERVIEW

user|rooms|chat|message|group|security_system
----|-----|----|-------|-----|---------------
id_user|id_room|id_chat|id_msg|friend_list|id_user|
name|id_chat|id_leader|from_user|nearby_user|token_key|
nickname|configuration_room|id_user_invite|seen_uder|to_user||
email|configuration_chat|id_group|msg_content|||
ip_addr|configuration_users||date_sent|||
passwor||||||

(group > user) <link> (rooms > chat > messages)

#Enhance Reame.md
https://guides.github.com/features/mastering-markdown/
http://www.emoji-cheat-sheet.com/