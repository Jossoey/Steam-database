SET DEFINE OFF;


DROP TABLE Soundtrack;
DROP TABLE DLC_Expands;
DROP TABLE HardwareRequirements;
DROP TABLE Describes;
DROP TABLE Tag;
DROP TABLE Achievement;
DROP TABLE Purchases;
DROP TABLE Plays;
DROP TABLE Invoice;
DROP TABLE Review;
DROP TABLE FriendsWith;
DROP TABLE Player;
DROP TABLE Product;
DROP TABLE Developer;
DROP TABLE Publisher;
DROP TABLE BankInfo;
DROP TABLE Account;
DROP TABLE Locale;


CREATE TABLE BankInfo (
	routingNumber	NUMBER(9),
	bankAddress		VARCHAR(100),
	PRIMARY KEY (routingNumber));

GRANT ALL PRIVILEGES ON BankInfo TO Public;


CREATE TABLE Tag (
	tagName	VARCHAR(100),
	PRIMARY KEY (tagName));

GRANT SELECT ON Tag TO Public;


CREATE TABLE Developer (
	devName	    VARCHAR(100),
	followers	NUMBER,
	website	    VARCHAR(100),
	PRIMARY KEY (devName));

GRANT SELECT ON Developer TO Public;


CREATE TABLE Locale (
	country	    CHAR(2),
	currency	CHAR(3),
	PRIMARY KEY (country));

GRANT SELECT ON Locale TO Public;


CREATE TABLE Account (
	steamID		        NUMBER(17),
	balance		        NUMBER(*,2),
	password		    VARCHAR(100),
	email			    VARCHAR(100) UNIQUE,
	username		    VARCHAR(20) UNIQUE,
	registrationDate	DATE,
	country		        CHAR(2) DEFAULT 'US',
	PRIMARY KEY (steamID),
	FOREIGN KEY (country) 
        REFERENCES Locale);

GRANT SELECT ON Account TO Public;


CREATE TABLE Publisher (
	steamID		        NUMBER(17),
	routingNumber	    NUMBER(9),
	firstName		    VARCHAR(50),	
	lastName		    VARCHAR(50),
	bankAccountNumber	NUMBER(10),
	PRIMARY KEY (steamID),
	FOREIGN KEY (steamID) 
        REFERENCES Account
        ON DELETE CASCADE,
    FOREIGN KEY (routingNumber) 
        REFERENCES BankInfo
        ON DELETE SET NULL);

GRANT SELECT ON Publisher TO Public;


CREATE TABLE Product (
	appID			    NUMBER,
	developerName	    VARCHAR(100) NOT NULL,
    publisherSteamID	NUMBER(17) NOT NULL,
    price			    NUMBER(*,2),
	title			    VARCHAR(100),
	releaseDate		    DATE,
	PRIMARY KEY (appID),
    FOREIGN KEY (developerName) 
        REFERENCES Developer(devName) 
        ON DELETE CASCADE,
    FOREIGN KEY (publisherSteamID) 
        REFERENCES Publisher(steamID) 
        ON DELETE CASCADE);

GRANT SELECT ON Product TO Public;


CREATE TABLE Soundtrack (
	appID		NUMBER,
	composer	VARCHAR(100),
	PRIMARY KEY (appID),
	FOREIGN KEY (appID) 
		REFERENCES Product
		ON DELETE CASCADE);

GRANT SELECT ON Soundtrack TO Public;


CREATE TABLE DLC_Expands (
	appID	NUMBER,
	dlcID	NUMBER(7),
	PRIMARY KEY (appID, dlcID),
	FOREIGN KEY (appID) 
        REFERENCES Product
        ON DELETE CASCADE);

GRANT SELECT ON DLC_Expands TO Public;


CREATE TABLE HardwareRequirements (
	appID		NUMBER,
	osVersion	VARCHAR(200),
	soundCard	VARCHAR(200),
	graphics	VARCHAR(200),
	processor	VARCHAR(200),
	storage	    VARCHAR(200),
	memory	    VARCHAR(200),
	PRIMARY KEY (appID, osVersion),
	FOREIGN KEY (appID) 
		REFERENCES Product
        ON DELETE CASCADE);

GRANT SELECT ON HardwareRequirements TO Public;


CREATE TABLE Describes (
	tagName	    VARCHAR(100),
	appID		NUMBER,
	PRIMARY KEY (tagName, appID),
	FOREIGN KEY (tagName) 
        REFERENCES Tag
        ON DELETE SET NULL,
	FOREIGN KEY (appID) 
		REFERENCES Product
		ON DELETE CASCADE);

GRANT SELECT ON Describes TO Public;


CREATE TABLE Review (
	reviewID	    	NUMBER(10),
	appID			    NUMBER NOT NULL,
	steamID		        NUMBER(17) NOT NULL,
	recommended	    	CHAR(1),
	reviewDate		    DATE,
	reviewDescription	VARCHAR(4000),
	PRIMARY KEY (reviewID),
	FOREIGN KEY (appID) 
        REFERENCES Product
        ON DELETE CASCADE,
	FOREIGN KEY (steamID) 
        REFERENCES Account
        ON DELETE CASCADE);

GRANT SELECT ON Review TO Public;


CREATE TABLE Invoice (
	invoiceNumber	NUMBER(19),
	appID			NUMBER DEFAULT 000000 NOT NULL ,
	steamID		    NUMBER(17) DEFAULT 00000000000000000 NOT NULL,
	total			NUMBER(*,2),
	invoiceDate		DATE,
	paymentMethod	VARCHAR(100),
	PRIMARY KEY (invoiceNumber),
	FOREIGN KEY (appID) 
        REFERENCES Product,
	FOREIGN KEY (steamID) 
        REFERENCES Account);

GRANT SELECT ON Invoice TO Public;


CREATE TABLE Player (
	steamID	    NUMBER(17),
	alias		VARCHAR(20),
	PRIMARY KEY (steamID),
	FOREIGN KEY (steamID) 
        REFERENCES Account
        ON DELETE CASCADE);

GRANT SELECT ON Player TO Public;


CREATE TABLE Plays (
	appID		NUMBER,
	steamID 	NUMBER(17),
	hours		NUMBER,
	PRIMARY KEY (appID, steamID),
	FOREIGN KEY (appID) 
        REFERENCES Product
        ON DELETE CASCADE,
    FOREIGN KEY (steamID) 
        REFERENCES Account
        ON DELETE CASCADE);

GRANT SELECT ON Plays TO Public;


CREATE TABLE FriendsWith (
	steamID	    NUMBER(17),
	friendsID	NUMBER(17),
    PRIMARY KEY (steamID, friendsID),
	FOREIGN KEY (steamID) 
        REFERENCES Account
        ON DELETE CASCADE,
	FOREIGN KEY (friendsID) 
        REFERENCES Account(steamID)
        ON DELETE CASCADE);

GRANT SELECT ON FriendsWith TO Public;


CREATE TABLE Achievement (
	achievementID		    NUMBER(10),
	appID				    NUMBER NOT NULL,
	steamID			        NUMBER(17) NOT NULL,
	percentOfPlayersEarned	NUMBER,
	description			    VARCHAR(4000),
	name				    VARCHAR(100),
	PRIMARY KEY (achievementID),
	FOREIGN KEY (appID) 
        REFERENCES Product
        ON DELETE CASCADE,
	FOREIGN KEY (steamID) 
        REFERENCES Account
        ON DELETE CASCADE);

GRANT SELECT ON Achievement TO Public;


CREATE TABLE Purchases (
	appID			NUMBER,
	steamID		    NUMBER(17),
	invoiceNumber	NUMBER(19) NOT NULL,
	PRIMARY KEY (appID, steamID),
	FOREIGN KEY (appID) 
		REFERENCES Product
        ON DELETE CASCADE,
	FOREIGN KEY (steamID) 
		REFERENCES Account
        ON DELETE CASCADE,
	FOREIGN KEY (invoiceNumber)
        REFERENCES Invoice
        ON DELETE CASCADE);

GRANT SELECT ON Purchases TO Public;


INSERT 
INTO   BankInfo (routingNumber, bankAddress)
VALUES (071921891, '3267 IN-32, Westfield, IN 46074, United States');

INSERT 
INTO   BankInfo (routingNumber, bankAddress)
VALUES (121143736, '75 River St, Santa Cruz, CA 95060, United States');

INSERT 
INTO   BankInfo (routingNumber, bankAddress)
VALUES (651241450, '5905 Berton Ave, Vancouver, BC V6S 0B3, Canada');

INSERT 
INTO   BankInfo (routingNumber, bankAddress)
VALUES (196054388, 'rondo ONZ 1, 00-124 Warszawa, Poland');

INSERT 
INTO   BankInfo (routingNumber, bankAddress)
VALUES (113080630, '401 W 42nd St, New York, NY 10036, United States');

INSERT 
INTO   BankInfo (routingNumber, bankAddress)
VALUES (317659925, 'At&T Center, 227 W Monroe St, Chicago, IL 60606, United States');

INSERT 
INTO   BankInfo (routingNumber, bankAddress)
VALUES (673046490, '5000 Sunset Blvd, Los Angeles, CA 90027, United States');

INSERT 
INTO   BankInfo (routingNumber, bankAddress)
VALUES (503732895, '2257 Irving St, San Francisco, CA 94122, United States');



INSERT 
INTO   Tag (tagName)
VALUES ('Sandbox');

INSERT 
INTO   Tag (tagName)
VALUES ('Roguelike');

INSERT
INTO   Tag (tagName)
VALUES ('Co-op');

INSERT
INTO   Tag (tagName)
VALUES ('FPS');

INSERT
INTO   Tag (tagName)
VALUES ('Action');

INSERT
INTO   Tag (tagName)
VALUES ('Class-Based');

INSERT
INTO   Tag (tagName)
VALUES ('Multiplayer');

INSERT 
INTO   Tag (tagName)
VALUES ('Cyberpunk');

INSERT 
INTO   Tag (tagName)
VALUES ('Open World');

INSERT 
INTO   Tag (tagName)
VALUES ('RPG');

INSERT 
INTO   Tag (tagName)
VALUES ('Sci-fi');

INSERT 
INTO   Tag (tagName)
VALUES ('Grand Strategy');

INSERT 
INTO   Tag (tagName)
VALUES ('Exploration');

INSERT 
INTO   Tag (tagName)
VALUES ('4X');

INSERT 
INTO   Tag (tagName)
VALUES ('Futuristic');

INSERT 
INTO   Tag (tagName)
VALUES ('Space');



INSERT 
INTO   Developer (devName, followers, website)
VALUES ('Re-Logic', 32172, 're-logic.com');

INSERT 
INTO   Developer (devName, followers, website)
VALUES ('Edmund McMillen and Florian Himsl', 34734, NULL);

INSERT
INTO   Developer (devName, followers, website)
VALUES ('Ghost Ship Games', 3819, 'ghostship.dk');

INSERT 
INTO   Developer (devName, followers, website)
VALUES ('CD Projekt Red', 315601, 'en.cdprojektred.com');

INSERT
INTO   Developer (devName, followers, website)
VALUES ('Forgotten Empires, Tantalus Media, Wicked Witch', 48327, NULL);

INSERT
INTO   Developer (devName, followers, website)
VALUES ('AMPLITUDE Studios', 41058, 'games2gether.com');

INSERT 
INTO   Developer (devName, followers, website)
VALUES ('Paradox Development Studio', 62469, 'paradoxplaza.com');



INSERT 
INTO   Locale (country, currency)
VALUES ('US', 'USD');

INSERT 
INTO   Locale (country, currency)
VALUES ('CA', 'CAD');

INSERT
INTO   Locale (country, currency)
VALUES ('ID', 'IDR');

INSERT
INTO   Locale (country, currency)
VALUES ('JP', 'YEN');

INSERT
INTO   Locale (country, currency)
VALUES ('SE', 'SWE');

INSERT
INTO   Locale (country, currency)
VALUES ('PL', 'PLN');



INSERT 
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (18512157936190734, 46.18, ':DZenith1983!', 'business@re-logic.com', 'Re-Logic', TO_DATE('2011-01-07', 'YYYY-MM-DD'), 'US');

INSERT 
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (80470692171337084, 22.23, 'Password123!', 'kitkathrina@email.com', 'kitkathrina', TO_DATE('2013-07-20', 'YYYY-MM-DD'), 'CA');

INSERT 
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (29149147156919113, 3.50, '1980Isaac(T_T)', 'edmund@edmundm.com', 'edmundmcmillen', TO_DATE('2019-02-14', 'YYYY-MM-DD'), 'US');

INSERT
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (20112012201320140, 102.40, 'CPSC304RIP', 'databaserock@yahoo.com', 'DBDBDB', TO_DATE('2021-01-01', 'YYYY-MM-DD'), 'CA');

INSERT
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (20222895264715893, 5.78, 'Ilikepancake10', 'pancakeki@gmail.com', 'Pancake', TO_DATE('2008-06-04', 'YYYY-MM-DD'), 'ID');

INSERT
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (12345612345600525, 30.54, 'fishandchips39', 'fishnco32@gmail.com', 'Fishyfishy', TO_DATE('2007-01-02', 'YYYY-MM-DD'), 'JP');

INSERT
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (23899509526857143, 0, 'iheartalt', 'johnny.silverhand@gmail.com', 'Samurai', TO_DATE('1994-05-01', 'YYYY-MM-DD'), 'PL');

INSERT
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (60485693251700274, 0, 'nathere2890', 'nat.sulat@rocketmail.com', 'Snat', TO_DATE('1999-09-30', 'YYYY-MM-DD'), 'US');

INSERT
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (59986018078832402, 0, 'hiimamy!!123', 'amy.wong@yahoo.com', 'awong', TO_DATE('2003-02-18', 'YYYY-MM-DD'), 'US');

INSERT
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (24675031984263507, 0, 'smithsonian999', 'smith.arnaud@gmail.com', 'The-sims', TO_DATE('2012-04-27', 'YYYY-MM-DD'), 'US');

INSERT
INTO   Account (steamID, balance, password, email, username, registrationDate, country)
VALUES (30672011703269611, 0, 'ilovepuppy45', 'zhang.gabrielle@gmail.com', 'PuppyMaster', TO_DATE('1999-07-07', 'YYYY-MM-DD'), 'SE');



INSERT 
INTO   Publisher (steamID, routingNumber, firstName, lastName, bankAccountNumber)
VALUES (18512157936190734, 071921891, 'Andrew', 'Spinks', 9610273645);

INSERT 
INTO   Publisher (steamID, routingNumber, firstName, lastName, bankAccountNumber)
VALUES (29149147156919113, 121143736, 'Edmund', 'McMillen', 2958105763);

INSERT
INTO   Publisher (steamID, routingNumber, firstName, lastName, bankAccountNumber)
VALUES (23899509526857143, 196054388, 'Johnny', 'Silverhand', 5521621685);

INSERT
INTO   Publisher (steamID, routingNumber, firstName, lastName, bankAccountNumber)
VALUES (60485693251700274, 113080630, 'Sulat', 'Nat', 2534957024);

INSERT
INTO   Publisher (steamID, routingNumber, firstName, lastName, bankAccountNumber)
VALUES (59986018078832402, 317659925, 'Amy', 'Wong', 7834422833);

INSERT
INTO   Publisher (steamID, routingNumber, firstName, lastName, bankAccountNumber)
VALUES (24675031984263507, 673046490, 'Arnaud', 'Smith', 0605908624);

INSERT
INTO   Publisher (steamID, routingNumber, firstName, lastName, bankAccountNumber)
VALUES (30672011703269611, 503732895, 'Gabrielle', 'Zhang', 0223723265);



INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (105600, 'Re-Logic', 18512157936190734, 10.99, 'Terraria', TO_DATE('2011-05-16', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (409210, 'Re-Logic', 18512157936190734, 5.69, 'Terraria: Official Soundtrack', TO_DATE('2015-10-13', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (113200, 'Edmund McMillen and Florian Himsl', 29149147156919113, 5.49, 'The Binding of Isaac', TO_DATE('2011-09-28', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (113204, 'Edmund McMillen and Florian Himsl', 29149147156919113, 3.29, 'The Binding of Isaac: Wrath of the Lamb', TO_DATE('2012-05-28', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (109150, 'CD Projekt Red', 23899509526857143, 79.99, 'Cyberpunk 2077', TO_DATE('2020-12-09', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (1228810, 'Forgotten Empires, Tantalus Media, Wicked Witch', 60485693251700274, 11.49, 'Age of Empires II: Definitive Edition Soundtrack', TO_DATE('2020-01-23', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (127557, 'AMPLITUDE Studios', 59986018078832402, 3.30, 'Endless Space 2 - Harmonic Memories Soundtrack', TO_DATE('2020-07-03', 'YYYY-MM-DD'));

INSERT
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (548430, 'Ghost Ship Games', 24675031984263507, 34.99, 'Deep Rock Galactic', TO_DATE('2020-05-13', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (281990, 'Paradox Development Studio', 30672011703269611, 43.99, 'Stellaris', TO_DATE('2016-05-09', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (492740, 'Paradox Development Studio', 30672011703269611, 7.79, 'Stellaris: Complete Soundtrack', TO_DATE('2016-07-12', 'YYYY-MM-DD'));

INSERT 
INTO   Product (appID, developerName, publisherSteamID, price, title, releaseDate)
VALUES (802640, 'Ghost Ship Games', 24675031984263507, 11.49, 'Deep Rock Galactic - Original Soundtrack Volume I + II', TO_DATE('2018-02-27', 'YYYY-MM-DD'));



INSERT 
INTO   Soundtrack (appID, composer)
VALUES (409210, 'Scott Lloyd Shelly/Resonance Array');

INSERT 
INTO   Soundtrack (appID, composer)
VALUES (1228810, 'Todd Masten and Semitone Media Group');

INSERT 
INTO   Soundtrack (appID, composer)
VALUES (127557, 'Arnaud Roy');

INSERT
INTO   Soundtrack (appID, composer)
VALUES (802640, 'Sophus Alf Agerbaek-Larsen');

INSERT
INTO   Soundtrack (appID, composer)
VALUES (492740, 'Andreas Waldetoft');



INSERT 
INTO   DLC_Expands (appID, dlcID)
VALUES (113204, 9281765);

INSERT 
INTO   DLC_Expands (appID, dlcID)
VALUES (113204, 1283090);

INSERT 
INTO   DLC_Expands (appID, dlcID)
VALUES (113204, 1283091);

INSERT 
INTO   DLC_Expands (appID, dlcID)
VALUES (113204, 1430610);

INSERT 
INTO   DLC_Expands (appID, dlcID)
VALUES (281990, 1140001);



INSERT 
INTO   HardwareRequirements (appID, osVersion, soundCard, graphics, processor, storage, memory)
VALUES (105600, 'Windows 7, 8/8.1, 10', NULL, '256MB Video Memory, Capable of Shader Model 2.0+', 'Dual Core 3.0 GHz', '200MB', '4GB');

INSERT
INTO   HardwareRequirements (appID, osVersion, soundCard, graphics, processor, storage, memory)
VALUES (409210, 'Windows 7, 8/8.1, 10', NULL, '256MB Video Memory, Capable of Shader Model 2.0+', 'Dual Core 3.0 GHz', '200MB', '4GB');

INSERT
INTO   HardwareRequirements (appID, osVersion, soundCard, graphics, processor, storage, memory)
VALUES (113200, 'Windows XP, Vista, 7', NULL, 'Direct X9.0c Compatible Card', '2.5 GHz', '50MB', '1GB');

INSERT
INTO   HardwareRequirements (appID, osVersion, soundCard, graphics, processor, storage, memory)
VALUES (113204, 'Windows XP, Vista, 7', NULL, 'Direct X9.0c Compatible Card', '5 GHz', '50MB', '2GB');

INSERT
INTO   HardwareRequirements (appID, osVersion, soundCard, graphics, processor, storage, memory)
VALUES (109150, 'Windows 7 or 10', NULL, 'NVIDIA GeForce GTX 780 or AMD Radeon RX 470', 'Intel Core i5-3570K or AMD FX-8310', '70 GB available space', '8 GB RAM');

INSERT
INTO   HardwareRequirements (appID, osVersion, soundCard, graphics, processor, storage, memory)
VALUES (281990, 
		'Windows® 7 SP1 64 Bit', 
		'Direct X 9.0c- compatible sound card', 
		'Nvidia® GeForce™ GTX 460 or AMD® ATI Radeon™ HD 5870 (1GB VRAM), or AMD® Radeon™ RX Vega 11 or Intel® HD Graphics 4600', 
		'Intel® iCore™ i3-530 or AMD® FX-6350', 
		'10 GB available space', 
		'4 GB RAM');

INSERT
INTO   HardwareRequirements (appID, osVersion, soundCard, graphics, processor, storage, memory)
VALUES (113204, 'Windows 10 64 Bit', NULL, 'NVIDIA 970 / AMD Radeon 290', '2.4 GHz Quad Core', '3 GB', '8 GB');



INSERT 
INTO   Describes (tagName, appID)
VALUES ('Sandbox', 105600);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Roguelike', 113200);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Space', 281990);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Grand Strategy', 281990);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Sci-fi', 281990);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Exploration', 281990);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('4X', 281990);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Cyberpunk', 109150);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Open World', 109150);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('RPG', 109150);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Sci-fi', 109150);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Futuristic', 109150);

INSERT
INTO   Describes (tagName, appID)
VALUES ('Co-op', 548430);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Space', 548430);

INSERT
INTO   Describes (tagName, appID)
VALUES ('FPS', 548430);

INSERT 
INTO   Describes (tagName, appID)
VALUES ('Roguelike', 548430);

INSERT
INTO   Describes (tagName, appID)
VALUES ('Class-Based', 548430);

INSERT
INTO   Describes (tagName, appID)
VALUES ('Action', 548430);



INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (4197854681354871230, 105600, 80470692171337084, 1.99, TO_DATE('2014-09-04 23:58:51', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (3829019275013328198, 409210, 80470692171337084, 5.69, TO_DATE('2018-03-21 14:15:16', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (2318120815620851132, 113200, 80470692171337084, 0.87, TO_DATE('2014-12-20 20:16:47', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (2318120815620851154, 113200, 20222895264715893, 0.87, TO_DATE('2014-12-20 20:15:45', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (1234567123456712354, 548430, 80470692171337084, 34.99, TO_DATE('2020-07-04 19:16:40', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (1234567123456712345, 548430, 20222895264715893, 34.99, TO_DATE('2020-07-04 19:16:50', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (1934297245921527950, 548430, 20112012201320140, 34.99, TO_DATE('2020-09-15 20:20:20', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (1934297245921527444, 802640, 20112012201320140, 11.49, TO_DATE('2020-09-15 20:20:50', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (1934297245921527445, 802640, 12345612345600525, 11.49, TO_DATE('2020-09-15 20:20:51', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (2318120567815620851, 548430, 12345612345600525, 34.99, TO_DATE('2020-07-25 10:40:24', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (8048204759201740539, 127557, 20112012201320140, 3.30, TO_DATE('2020-10-11 19:32:16', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (7301948273027564017, 127557, 20222895264715893, 3.30, TO_DATE('2021-01-31 11:42:10', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');

INSERT 
INTO   Invoice (invoiceNumber, appID, steamID, total, invoiceDate, paymentMethod)
VALUES (7301948273027564020, 109150, 20222895264715893, 79.99, TO_DATE('2021-01-31 11:42:10', 'YYYY-MM-DD HH24:MI:SS'), 'Steam Wallet');


INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (113200, 20222895264715893, 2318120815620851154);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (105600, 80470692171337084, 4197854681354871230);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (109150, 20222895264715893, 7301948273027564020);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (802640, 20112012201320140, 1934297245921527444);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (802640, 12345612345600525, 1934297245921527445);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (409210, 80470692171337084, 3829019275013328198);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (113200, 80470692171337084, 2318120815620851132);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (548430, 20222895264715893, 2318120567815620851);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (548430, 80470692171337084, 1234567123456712354);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (548430, 20112012201320140, 1234567123456712345);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (548430, 12345612345600525, 1934297245921527950);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (127557, 20112012201320140, 8048204759201740539);

INSERT 
INTO   Purchases (appID, steamID, invoiceNumber)
VALUES (127557, 20222895264715893, 7301948273027564017);



INSERT 
INTO   Review (reviewID, appID, steamID, recommended, reviewDate, reviewDescription)
VALUES (1029384716, 409210, 80470692171337084, '1', TO_DATE('2020-06-29', 'YYYY-MM-DD'), 'Best $2 I''ever spent.');

INSERT
INTO   Review (reviewID, appID, steamID, recommended, reviewDate, reviewDescription)
VALUES (9058293571, 113200, 20222895264715893, '0', TO_DATE('2016-03-08', 'YYYY-MM-DD'), 'Not my type of game, sorry');

INSERT
INTO   Review (reviewID, appID, steamID, recommended, reviewDate, reviewDescription)
VALUES (9058293570, 113200, 80470692171337084, '1', TO_DATE('2016-03-07', 'YYYY-MM-DD'), 'A great game with endless replayability!');

INSERT 
INTO   Review (reviewID, appID, steamID, recommended, reviewDate, reviewDescription)
VALUES (1594826730, 548430, 20222895264715893, '1', TO_DATE('2020-06-29', 'YYYY-MM-DD'), 'FUN FUN FUN. It is a dwarf in space, you cannot go wrong with that!');

INSERT 
INTO   Review (reviewID, appID, steamID, recommended, reviewDate, reviewDescription)
VALUES (1594826732, 548430, 20112012201320140, '1', TO_DATE('2020-07-15', 'YYYY-MM-DD'), 'Fun solo and with friends. I have no clue how I never came across this game earlier, because I would''ve definitely been playing this if I found it last year when I was bored of everything else I had.');

INSERT 
INTO   Review (reviewID, appID, steamID, recommended, reviewDate, reviewDescription)
VALUES (1594826480, 548430, 80470692171337084, '1', TO_DATE('2020-07-21', 'YYYY-MM-DD'), 'Good if you play with friends!');

INSERT 
INTO   Review (reviewID, appID, steamID, recommended, reviewDate, reviewDescription)
VALUES (1594826733, 548430, 12345612345600525, '0', TO_DATE('2020-07-20', 'YYYY-MM-DD'), 'Very linear, gets boring pretty fast');



INSERT 
INTO   Player (steamID, alias)
VALUES (80470692171337084, 'kitkathrina');

INSERT 
INTO   Player (steamID, alias)
VALUES (29149147156919113, 'edmundmcchicken');

INSERT 
INTO   Player (steamID, alias)
VALUES (20112012201320140, 'Raccoonmeat');

INSERT 
INTO   Player (steamID, alias)
VALUES (20222895264715893, 'BOBOBOBS');

INSERT 
INTO   Player (steamID, alias)
VALUES (12345612345600525, 'Plebeian');



INSERT 
INTO   Plays (appID, steamID, hours)
VALUES (105600, 80470692171337084, 445.1);

INSERT 
INTO   Plays (appID, steamID, hours)
VALUES (113200, 80470692171337084, 178.3);

INSERT 
INTO   Plays (appID, steamID, hours)
VALUES (113200, 20222895264715893, 20.3);

INSERT 
INTO   Plays (appID, steamID, hours)
VALUES (548430, 20112012201320140, 300.5);

INSERT 
INTO   Plays (appID, steamID, hours)
VALUES (548430, 80470692171337084, 150.5);

INSERT 
INTO   Plays (appID, steamID, hours)
VALUES (548430, 20222895264715893, 62.4);

INSERT 
INTO   Plays (appID, steamID, hours)
VALUES (548430, 12345612345600525, 10.2);



INSERT 
INTO   FriendsWith (steamID, friendsID)
VALUES (80470692171337084, 20112012201320140);

INSERT 
INTO   FriendsWith (steamID, friendsID)
VALUES (20222895264715893, 20112012201320140);

INSERT 
INTO   FriendsWith (steamID, friendsID)
VALUES (80470692171337084, 12345612345600525);

INSERT 
INTO   FriendsWith (steamID, friendsID)
VALUES (12345612345600525, 20112012201320140);

INSERT 
INTO   FriendsWith (steamID, friendsID)
VALUES (12345612345600525, 20222895264715893);



INSERT 
INTO   Achievement (AchievementID, appID, steamID, percentOfPlayersEarned, description, name)
VALUES (1039582740, 105600, 80470692171337084, 12.25, 'Complete your 50th quest for the angler.', 'Fast and Fishious');

INSERT 
INTO   Achievement (AchievementID, appID, steamID, percentOfPlayersEarned, description, name)
VALUES (8302857194, 113200, 80470692171337084, 6.7, 'Kill Satan with Samson', 'Blood Rights');

INSERT 
INTO   Achievement (AchievementID, appID, steamID, percentOfPlayersEarned, description, name)
VALUES (4567981232, 548430, 20112012201320140, 2.3, 'Complete an elite deep dive', 'Elite Diver');

INSERT 
INTO   Achievement (AchievementID, appID, steamID, percentOfPlayersEarned, description, name)
VALUES (4567981233, 548430, 20112012201320140, 20.4, 'Complete 25 Milestones', 'Management Approve');

INSERT 
INTO   Achievement (AchievementID, appID, steamID, percentOfPlayersEarned, description, name)
VALUES (7382038759, 113200, 80470692171337084, 3.2, 'Beat Mom in Hardmode', 'Eternal Mom');

SET DEFINE ON;