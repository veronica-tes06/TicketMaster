-- Test database setup for TicketMaster
-- Drops and recreates schema with seed data suitable for tests

DROP DATABASE IF EXISTS ticketmaster_test;
CREATE DATABASE ticketmaster_test;
USE ticketmaster_test;

DROP TABLE IF EXISTS accounts;
CREATE TABLE IF NOT EXISTS `accounts` (
  `accID` INT AUTO_INCREMENT PRIMARY KEY,
  `accEmail` VARCHAR(40) NOT NULL,
  `accPassword` VARCHAR(20) NOT NULL,
  `accAdmin` BOOLEAN NOT NULL DEFAULT FALSE
);

DROP TABLE IF EXISTS events;
CREATE TABLE IF NOT EXISTS `events` (
  `eventID` INT AUTO_INCREMENT PRIMARY KEY,
  `eventName` VARCHAR(100) NOT NULL,
  `performer` VARCHAR(100) NOT NULL,
  `eventLocation` VARCHAR(100) NOT NULL,
  `eventDate` VARCHAR(100) NOT NULL,
  `eventTime` VARCHAR(100) NOT NULL,
  `eventTicketMaxAMT` VARCHAR(100) NOT NULL,
  `eventTicketMinAMT` VARCHAR(100) NOT NULL
);

DROP TABLE IF EXISTS eventDetails;
CREATE TABLE IF NOT EXISTS `eventDetails` (
  `accID` INT NOT NULL,
  `eventID` INT NOT NULL,
  `eventTicketAMT` VARCHAR(100) NOT NULL
);

-- Seed baseline users
INSERT INTO accounts (accEmail, accPassword, accAdmin)
VALUES ('sham@gmail.com', 'shamjamm', 0);

INSERT INTO accounts (accEmail, accPassword, accAdmin)
VALUES ('admin@gmail.com', 'adminadmin', 1);

-- Seed events (dates relative to 2025-11-16 make two future events)
INSERT INTO events (eventName, eventLocation, performer, eventDate, eventTime, eventTicketMaxAMT, eventTicketMinAMT)
VALUES 
('Sham\'s Jam', 'His Kitchen', 'Sham', '14-11-2025', '19:30', '5000', '0'),
('The Weeknd', 'Madison Square Garden', 'TheWeeknd', '01-01-2026', '20:30', '30000', '0'),
('before', 'before', 'ghost of past', '01-01-2021', '20:30', '30000', '0'),
('after', 'after', 'ghost of future', '01-01-2026', '20:30', '30000', '0'),
('Big Leagues', 'Croke Park', 'Sham', '19-02-2022', '9:00', '1444', '0');

-- Seed eventDetails, matching IDs from inserts above
INSERT INTO eventDetails (accID, eventID, eventTicketAMT)
VALUES
(1, 1, '2'), 
(1, 3, '4'), 
(1, 4, '4'), 
(2, 2, '1'), 
(2, 3, '1'), 
(2, 4, '1'), 
(2, 2, '3');
