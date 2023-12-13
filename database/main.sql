/* To run, use "mysql -u root -p < 'filepath'" */

/* Creates the database, 'psirt' */
CREATE DATABASE IF NOT EXISTS psirt;
USE psirt;

/* Creates tables for database */
CREATE TABLE user (
    userID INT NOT NULL AUTO_INCREMENT,
    firstname CHAR(25) NOT NULL,
    lastname CHAR(25) NOT NULL,
    type CHAR(10) NOT NULL,
    address VARCHAR(50) NOT NULL,
    phoneNumber VARCHAR(25) NOT NULL,
    username CHAR(25) NOT NULL,
    password CHAR(25) NOT NULL,
    emailAddress CHAR(50) NOT NULL,
    ipAddress CHAR(30) NOT NULL,
    CONSTRAINT USER_PK PRIMARY KEY (userID)
);


CREATE TABLE `order` (
    orderID INT NOT NULL AUTO_INCREMENT,
    service VARCHAR(50) NOT NULL,
    date DATETIME NOT NULL,
    state CHAR(25) NOT NULL,
    clientID INT NOT NULL,
    sitterID INT NULL,
    archived TINYINT(1) NOT NULL,
    CONSTRAINT ARCHIVED_CHK CHECK (archived IN (0, 1)),
    CONSTRAINT ORDER_PK PRIMARY KEY (orderID),
    CONSTRAINT ORDER_USERCLIENT_FK FOREIGN KEY (clientID) REFERENCES user(userID),
    CONSTRAINT ORDER_USERSITTER_FK FOREIGN KEY (sitterID) REFERENCES user(userID)
);


CREATE TABLE comment (
    commentID INT NOT NULL AUTO_INCREMENT,
    comment VARCHAR(255) NOT NULL,
    userID INT NOT NULL,
    orderID INT NOT NULL,
    date DATETIME NOT NULL,
    CONSTRAINT COMMENT_PK PRIMARY KEY (commentID),
    CONSTRAINT COMMENT_USER_FK FOREIGN KEY (userID) REFERENCES user(userID),
    CONSTRAINT COMMENT_ORDER_FK FOREIGN KEY (orderID) REFERENCES `order`(orderID)
);


CREATE TABLE request (
    requestID INT NOT NULL AUTO_INCREMENT,
    orderID INT NOT NULL,
    clientID INT NOT NULL,
    sitterID INT NOT NULL,
    handlerID INT NOT NULL,
    confirm TINYINT(1) NOT NULL,
    CONSTRAINT CONFIRM_CHK CHECK (confirm IN (0, 1)),
    CONSTRAINT REQUEST_PK PRIMARY KEY (requestID),
    CONSTRAINT REQUEST_ORDER_FK FOREIGN KEY (orderID) references `order`(orderID),
    CONSTRAINT REQUEST_USERCLIENT_FK FOREIGN KEY (clientID) references user(userID),
    CONSTRAINT REQUEST_USERSITTER_FK FOREIGN KEY (sitterID) references user(userID),
    CONSTRAINT REQUEST_USERHANDLER_FK FOREIGN KEY (handlerID) references user(userID)
);


CREATE TABLE `order_type` (
    `orderTypeID` INT NOT NULL AUTO_INCREMENT,
    `type_name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`orderTypeID`)
);




/* Inserts test data for database */
/* Users */
/* Clients */
INSERT INTO user VALUES(null, 'Michael', 'Scandiffio', 'user', '123 Sesame Street', '5163758968', 'username1', 'password1', 'michaelscandiffio@mail.adelphi.edu', '192.160.131.34');
INSERT INTO user VALUES(null, 'Roya', 'Parsa', 'user', '124 Sesame Street', '1234567890', 'username2', 'password2', 'royaparsa@mail.adelphi.edu', 'oth.er.num.bers');
INSERT INTO user VALUES(null, 'John', 'Smith', 'user', '125 Sesame Street', '2345678901', 'username3', 'password3', 'johnsmith@notreal.com', '123.456.789.01');
/* Sitters */
INSERT INTO user VALUES(null, 'Person', 'Four', 'sitter', '444 Sesame Street', '4444444444', 'username4', 'password4', '444@notreal.com', '444.444.444.44');
INSERT INTO user VALUES(null, 'Person', 'Five', 'sitter', '555 Sesame Street', '5555555555', 'username5', 'password5', '555@notreal.com', '555.555.555.55');
INSERT INTO user VALUES(null, 'Person', 'Six', 'sitter', '666 Sesame Street', '6666666666', 'username6', 'password6', '666@notreal.com', '666.666.666.66');
/* Handlers */
INSERT INTO user VALUES(null, 'Person', 'Seven', 'handler', '777 Sesame Street', '7777777777', 'username7', 'password7', '777@notreal.com', '777.777.777.77');
INSERT INTO user VALUES(null, 'Person', 'Eight', 'handler', '888 Sesame Street', '8888888888', 'username8', 'password8', '888@notreal.com', '888.888.888.88');
INSERT INTO user VALUES(null, 'Person', 'Nine', 'handler', '999 Sesame Street', '9999999999', 'username9', 'password9', '999@notreal.com', '999.999.999.99');


/* Orders */
INSERT INTO `order` VALUES(null, 'Dog wash', '2023-12-01 10:30:00', 'Pending', 1, null, 0);
INSERT INTO `order` VALUES(null, 'Dog grooming', '2023-12-03 14:45:00', 'Pending', 2, null, 0);
INSERT INTO `order` VALUES(null, 'Dog walk', '2023-12-05 13:00:00', 'Pending', 3, null, 0);


/* Comments */
INSERT INTO comment VALUES(null, 'Can you knock on my front door, not ring my doorbell?', 1, 1, '2023-11-29 15:04:23');
INSERT INTO comment VALUES(null, 'The leash is on the back of my front door', 2, 2, '2023-11-31 16:23:04');
INSERT INTO comment VALUES(null, 'Please dont walk past my next door neighbors home', 3, 3, '2023-11-30 18:46:45');


/* Requests */
INSERT INTO request VALUES(null, 1, 1, 4, 7, 0);
INSERT INTO request VALUES(null, 2, 2, 5, 8, 0);
INSERT INTO request VALUES(null, 3, 3, 6, 9, 0);


/* Order Type */
INSERT INTO `order_type` (`type_name`) VALUES
    ('Dog Walking'),
    ('Dog Feeding'),
    ('Grooming'),
    ('Cat Sitting'),
    ('Pet Training'),
    ('Overnight Pet Care');
