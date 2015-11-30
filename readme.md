#CONSTRUCT System 
Rooms {group[user] <-> message <-> group[user]}

### Rooms 
The overview of all chat avaible including all the configuration of them.

### Message 
A sending unit that can contain a lot of type of media : text mainly, but also images , sound, link and other...

### User 
An logged entity that have personnal informations.

### A group
A list regrouping, friend list, nearby users, recently seen user, for each users

### Chat
The interface that allow users/group to communicate with each other.

### Security_System
A very basic security system.

#THEORETICAL ARCHITECTURES
	- Main{
		class Authentification {}
		class BackPanel {}
		class User {}
		class Group {}
		class Controller {}
		class Database {}
		class sChat {}
		class Tools {}
	}

###FUNCTIONNAL ARCHITECTURES 
	Authentification.class.php{
		description : "first level class of authentification process",
		functions : [
		],
		class-parents : "",
	}
	BackPanel.class.php{
		description : "back office, mainly created to retreive data",
		functions : [
		],
		class-parents : "",
	}
	User.class.php{
		description: "first level class of user process",
		functions : [
		],
		class-parents : "",
	}
	Group.class.php{
		description: "first level class of group management",
		functions : [
		],
		class-parents : "",
	}
	Controller.class.php{
		description : "call of functionnal class or functions, creating the root of the application",
		functions : [
		],
		class-parents : "",
	}
	Database.class.php{
		description: "all database related queries",
		functions : [
		],
		class-parents : "",
	}
	sChat.class.php{
		description: "first level of application, will call all simplified function of the project",
		functions : [
			'Controller', 'view', 'model', 'Authentification', 'user management', 'group managnement', 'chatRooms'
		],
		class-parents : ""
	}
	Tools.class.php{
		description : "Bunch of tools function called statically in needed situation",
		functions : [
		],
		class-parents : "",
	}

#TODOLIST/IMPROVEMENTS
- [ ] User
- [ ] Group
- [ ] Message
- [ ] Session

- [ ] Md5 password
- [ ] Authentification process (multi user connection)
- [ ] Define id_user_currently_connected
- [ ] Security Process of $this->current_id_user_connected (all function related to it)
- [ ] Friend list improvement (add friend with email, nickname, etc)

#RANDOM ALGO && SELFASKS
User doesn't exists -> register -> user exists

When init dB ? user && group making || rooms && chat && messages 

Db problem : all functions are dependant to  `$pdo_object`, to call them externally to the Class, how you we do, without having the arguments ? 

```php
class Db{
	/*
	* Problem 
	*/
	function __construct()
	{
		$datArg = necessarySuff();
		randomDbFunction($datArg);	
	}
	function randomDbFunction(randomArgDependant)
	{
		//randomCodeWithArgDependant
	}
	/*
	* Solution 1
	* Proceed the same way for each functions
	* Negative factor : performance loss
	* Positive factor : general isolation
	*/
	function callToRandomFunctions()
	{
		$datArg = necessarySuff();
		randomDbFunction($datArg);	
	}
	/*
	* Solution 2
	* Negative factor : not sure if it's stable (depending on context)
	* Positive factor : general isolation plus functionnal programming respectfull
	*/
	private datArg;
	function __construct()
	{
		$this->datArg = necessarySuff();
		$this->callToRandomFunction();
	}
	function callToRandomFunction()
	{
		randomDbFunction($this->datArg);
	}
	function randomDbFunction($this->datArg)
	{
		//randomCodeWithArgDependant
	}
}
```

#DATABASE OVERVIEW
user|rooms|chat|message|group|security_system
----|-----|----|-------|-----|---------------
id_user	|id_room	|id_chat	|id_msg	|id_group	|id_user	|
name	|id_chat	|id_leader	|from_user	|friend_list	|token_key	|
nickname	|conf_room	|id_user_invite	|nearby_user	|to_user	|	|
email	|conf_chat	|id_group	|	|seen_uder	|	|
ip_addr	|conf_users	|	|	|msg_content	|	|
password	|	|	|	|date_sent	|	|

(group > user) <link> (rooms > chat > messages)

#Enhance Reame.md
https://guides.github.com/features/mastering-markdown/

<!-- http://www.emoji-cheat-sheet.com/ -->