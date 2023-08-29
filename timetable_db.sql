CREATE DATABASE IF NOT EXISTS `timetable_db`;
USE `timetable_db`;

CREATE TABLE timetable (
    id INT PRIMARY KEY AUTO_INCREMENT,
    department VARCHAR(50),
    class_name VARCHAR(50),
    semester VARCHAR(20),
    timetable_data TEXT
);
