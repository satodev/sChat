#CONSTRUCT System 
Rooms {group[user] <-> message <-> group[user]}

### Rooms 
The overview of all chat avaible including all the configuration of them.

### Message 
A sending unit that can contain a lot of type of media : text mainly, but also images , sound, link and other...

### User 
An loged entity that have personnal informations.

### A group
A list regrouping, friend list, nearby users, recently seen user, for each users

### Chat
The interface that allow user/group to communicate with each other.

### Security_System
A very basic security system.

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


#TODOLIST/IMPROVEMENTS
- [ ] User
- [ ] Group
- [ ] Message
- [ ] Session


#RANDOM ALGO && SELFASKS
User doesn't exists -> register -> user exists

When init dB ? user && group making || rooms && chat && messages 

#DATABASE OVERVIEW
user|rooms|chat|message|group|security_system
----|-----|----|-------|-----|---------------
id_user	|id_room	|id_chat	|id_msg	|id_group	|id_user	|
name	|id_chat	|id_leader	|from_user	|friend_list	|token_key	|
nickname	|configuration_room	|id_user_invite	|nearby_user	|to_user	|	|
email	|configuration_chat	|id_group	|	|seen_uder	|	|
ip_addr	|configuration_users	|	|	|msg_content	|	|
password	|	|	|	|date_sent	|	|

(group > user) <link> (rooms > chat > messages)

#Enhance Reame.md
https://guides.github.com/features/mastering-markdown/

http://www.emoji-cheat-sheet.com/