-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2017 at 01:28 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `banking_system`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `FD_DELETE` ()  NO SQL
BEGIN
DECLARE FD_DEL CONDITION FOR SQLSTATE '45007';
DECLARE F_ID INT(10);
DECLARE F_ST_D DATE;
DECLARE F_MT_D DATE;
declare cur1 cursor for select FD_ID_PK,FD_START_DATE,FD_MATURITY_DATE from fixed_deposit_data WHERE CURDATE()>=FD_MATURITY_DATE;
open cur1;
start_loop: loop
        fetch cur1 into F_ID,F_ST_D,F_MT_D;
        
            DELETE FROM fixed_deposit_data WHERE FD_ID_PK = F_ID;
            end loop;
            close cur1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UPDATE_FD_AMT` ()  NO SQL
BEGIN
DECLARE F_ID INT(10);
DECLARE F_INT_R INT(10);
declare cur2 cursor for SELECT FD_ID_PK,FD_INTEREST_RATE FROM fixed_deposit_data WHERE CURRENT_DATE = UPDATE_DATE;

open cur2;
start_loop: loop
        fetch cur2 into F_ID,F_INT_R;
        
            UPDATE fixed_deposit_data SET  FD_MATURITY_AMOUNT=FD_AMOUNT*(1+(F_INT_R * 0.01))  WHERE FD_ID_PK = F_ID;
            
            UPDATE fixed_deposit_data SET UPDATE_DATE = NOW() WHERE FD_ID_PK = F_ID; 
            end loop;
            close cur2;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UPDATE_L_AMT` ()  NO SQL
BEGIN
DECLARE A_ID INT(10);
DECLARE L_TYPE VARCHAR(50);
DECLARE L_INT_RATE INT(10);
DECLARE A_TYPE VARCHAR(10);
declare cur4 cursor for select ACC_ID_FK,LOAN_TYPE_FK from loan_sanctions WHERE CURRENT_DATE = UPDATE_DATE and CURRENT_DATE < L_END_DATE ;

open cur4;
start_loop: loop
        fetch cur4 into A_ID,L_TYPE;
        SELECT LOAN_RATE INTO L_INT_RATE FROM loan_details WHERE  LOAN_TYPE_PK = L_TYPE;
        
            UPDATE loan_sanctions SET 
           P_LOAN_AMOUNT=I_LOAN_AMOUNT*(1+(L_INT_RATE*0.01))  WHERE ACC_ID_Fk = A_ID ;
           UPDATE loan_sanctions SET UPDATE_DATE = NOW() WHERE ACC_ID_FK = A_ID;
            end loop;
            close cur4;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UPDATE_S_AMT` ()  NO SQL
BEGIN
DECLARE A_ID INT(10);
DECLARE S_INT_RATE INT(10);
DECLARE A_TYPE VARCHAR(10);
declare cur3 cursor for select ACC_ID_PK,ACC_TYPE from account WHERE ACC_TYPE = 'SAVINGS' AND  CURRENT_DATE = UPDATE_DATE;
SELECT S_INTEREST_RATE INTO S_INT_RATE FROM account_details WHERE  ACC_TYPE = 'SAVINGS';
open cur3;
start_loop: loop
        fetch cur3 into A_ID,A_TYPE;
        
            UPDATE account SET 
           ACC_BALANCE=ACC_BALANCE*(1+(S_INT_RATE*0.01))  WHERE ACC_ID_Pk = A_ID AND ACC_TYPE = A_TYPE;
           UPDATE account SET UPDATE_DATE = NOW() WHERE ACC_ID_PK = A_ID;
            end loop;
            close cur3;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `ACC_ID_PK` int(10) NOT NULL,
  `CUST_ID_FK` int(10) NOT NULL,
  `ACC_BALANCE` int(15) NOT NULL,
  `UPDATE_DATE` date NOT NULL,
  `ACC_TYPE` varchar(15) NOT NULL,
  `BR_ID_FK` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`ACC_ID_PK`, `CUST_ID_FK`, `ACC_BALANCE`, `UPDATE_DATE`, `ACC_TYPE`, `BR_ID_FK`) VALUES
(1, 1, 513640, '2017-11-13', 'SAVINGS', 1),
(2, 2, 30000, '2017-11-13', 'CURRENT', 1),
(7, 1, 100000, '2017-11-13', 'LOAN', 1),
(8, 1, 100000, '2017-11-13', 'LOAN', 1),
(9, 4, 33333, '2017-11-13', 'ACCOUNT TYPE', 1),
(10, 5, 100000, '2017-11-13', 'CURRENT', 1);

-- --------------------------------------------------------

--
-- Table structure for table `account_details`
--

CREATE TABLE `account_details` (
  `ACC_TYPE` varchar(10) NOT NULL,
  `S_INTEREST_RATE` int(5) NOT NULL,
  `S_MIN_BALANCE` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_details`
--

INSERT INTO `account_details` (`ACC_TYPE`, `S_INTEREST_RATE`, `S_MIN_BALANCE`) VALUES
('CURRENT', 0, 0),
('SAVINGS', 3, 1000),
('LOAN', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `BR_ID_PK` int(10) NOT NULL,
  `BR_NAME` varchar(30) NOT NULL,
  `BR_ADDRESS` varchar(30) NOT NULL,
  `MANAGER_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`BR_ID_PK`, `BR_NAME`, `BR_ADDRESS`, `MANAGER_ID`) VALUES
(1, 'SBI VENGALRAO', 'HYDERABAD', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CUST_ID_PK` int(10) NOT NULL,
  `CUST_NAME` varchar(20) NOT NULL,
  `CUST_DOB` date NOT NULL,
  `CUST_GENDER` varchar(1) NOT NULL,
  `CUST_PHONE` varchar(15) NOT NULL,
  `CUST_MAILID` varchar(20) NOT NULL,
  `CUST_ADDRESS` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CUST_ID_PK`, `CUST_NAME`, `CUST_DOB`, `CUST_GENDER`, `CUST_PHONE`, `CUST_MAILID`, `CUST_ADDRESS`) VALUES
(1, 'MAN', '1998-09-18', 'F', '9583568690', 'JM@IITBBS.AC.IN', 'HYDERABAD'),
(2, 'TAYLOR', '1978-02-11', 'F', '8989898989', 'TS@GMAIL.COM', 'HYDERABAD'),
(3, 'DEMI', '1997-06-05', 'F', '5676567656', 'DL@GMAIL.COM', 'CHENNAI'),
(4, 'SFFASH', '2005-09-10', 'F', '3333333333', 'TER', 'HFDS'),
(5, 'FV', '2019-09-08', 'F', '777', 'TER', 'FDS');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EMP_ID_PK` int(10) NOT NULL,
  `EMP_NAME` varchar(20) NOT NULL,
  `EMP_GENDER` varchar(1) NOT NULL,
  `EMP_DOB` date NOT NULL,
  `EMP_PHONE_NO` varchar(15) NOT NULL,
  `EMP_ADDRESS` varchar(30) NOT NULL,
  `EMP_SALARY` int(20) NOT NULL,
  `EMP_ROLE` varchar(10) NOT NULL,
  `BR_ID_FK` int(10) NOT NULL,
  `EMP_PWD` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EMP_ID_PK`, `EMP_NAME`, `EMP_GENDER`, `EMP_DOB`, `EMP_PHONE_NO`, `EMP_ADDRESS`, `EMP_SALARY`, `EMP_ROLE`, `BR_ID_FK`, `EMP_PWD`) VALUES
(1, 'PIGGS', 'M', '1992-11-01', '9999999999', 'HYDERABAD', 200000, 'MANAGER', 1, '123'),
(4, 'SHAWN', 'M', '1999-09-19', '8787878787', 'HYDERABAD', 150000, 'EMPLOYEE', 1, '123'),
(5, 'HSDFG', 'F', '1997-06-07', '8888888888', 'JDFBHHD', 199999, 'EMPLOYEE', 1, '123');

--
-- Triggers `employee`
--
DELIMITER $$
CREATE TRIGGER `EMP_DELETE` AFTER DELETE ON `employee` FOR EACH ROW BEGIN

INSERT INTO `EMP_HISTORY`VALUES (OLD.EMP_ID_PK,OLD.EMP_NAME,OLD.EMP_ROLE,OLD.EMP_GENDER,OLD.EMP_DOB,OLD.EMP_PHONE_NO,OLD.EMP_ADDRESS,OLD.EMP_SALARY,OLD.BR_ID_FK,NOW());
				
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_login_details`
--

CREATE TABLE `employee_login_details` (
  `EMP_ID_FK` int(10) NOT NULL,
  `EMP_PASSWORD` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_login_details`
--

INSERT INTO `employee_login_details` (`EMP_ID_FK`, `EMP_PASSWORD`) VALUES
(1, '123'),
(4, '123'),
(5, '123');

-- --------------------------------------------------------

--
-- Table structure for table `emp_history`
--

CREATE TABLE `emp_history` (
  `ID` int(10) NOT NULL,
  `NAME` varchar(20) NOT NULL,
  `ROLE` varchar(10) NOT NULL,
  `GENDER` varchar(1) NOT NULL,
  `DOB` date NOT NULL,
  `PHONE_NO` varchar(15) NOT NULL,
  `ADDRESS` varchar(30) NOT NULL,
  `SALARY` int(20) NOT NULL,
  `BR_ID` int(10) NOT NULL,
  `RESIGNATION_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emp_history`
--

INSERT INTO `emp_history` (`ID`, `NAME`, `ROLE`, `GENDER`, `DOB`, `PHONE_NO`, `ADDRESS`, `SALARY`, `BR_ID`, `RESIGNATION_DATE`) VALUES
(1, 'PIGGS', 'MANAGER', 'M', '2017-11-07', '9999999999', 'BHUBANESWAR', 200000, 4, '2017-11-13'),
(2, 'SURYA', 'EMPLOYEE', 'M', '1998-07-09', '9898989898', 'HYDERABAD', 150000, 1, '2017-11-13'),
(3, 'RAVI', 'EMPLOYEE', 'M', '1997-06-07', '9999999999', 'HYDERABAD', 150000, 1, '2017-11-13');

-- --------------------------------------------------------

--
-- Table structure for table `fd_history`
--

CREATE TABLE `fd_history` (
  `FD_ID` int(10) NOT NULL,
  `ACC_ID` int(10) NOT NULL,
  `FD_S_DATE` date NOT NULL,
  `FD_MAT_DATE` date NOT NULL,
  `I_FD_AMT` int(20) NOT NULL,
  `FD_MAT_AMT` int(20) NOT NULL,
  `FD_DEL_DATE` date NOT NULL,
  `PENALTY` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fd_history`
--

INSERT INTO `fd_history` (`FD_ID`, `ACC_ID`, `FD_S_DATE`, `FD_MAT_DATE`, `I_FD_AMT`, `FD_MAT_AMT`, `FD_DEL_DATE`, `PENALTY`) VALUES
(1, 1, '2017-11-13', '2017-11-11', 100000, 105000, '2017-11-13', 0),
(4, 1, '2017-11-13', '2017-11-01', 100000, 105000, '2017-11-13', 0),
(5, 1, '2017-11-13', '2017-11-10', 200000, 210000, '2017-11-13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `fixed_deposit_data`
--

CREATE TABLE `fixed_deposit_data` (
  `FD_ID_PK` int(10) NOT NULL,
  `ACC_ID_FK` int(10) NOT NULL,
  `FD_AMOUNT` int(20) NOT NULL,
  `FD_INTEREST_RATE` int(10) NOT NULL,
  `FD_START_DATE` date NOT NULL,
  `FD_MATURITY_DATE` date NOT NULL,
  `UPDATE_DATE` date NOT NULL,
  `FD_MATURITY_AMOUNT` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fixed_deposit_data`
--

INSERT INTO `fixed_deposit_data` (`FD_ID_PK`, `ACC_ID_FK`, `FD_AMOUNT`, `FD_INTEREST_RATE`, `FD_START_DATE`, `FD_MATURITY_DATE`, `UPDATE_DATE`, `FD_MATURITY_AMOUNT`) VALUES
(6, 1, 100000, 6, '2017-11-13', '2019-09-03', '2017-11-13', 106000);

--
-- Triggers `fixed_deposit_data`
--
DELIMITER $$
CREATE TRIGGER `FD_BREAK_T` BEFORE DELETE ON `fixed_deposit_data` FOR EACH ROW BEGIN

IF OLD.FD_MATURITY_DATE<=CURDATE() THEN

UPDATE ACCOUNT SET ACC_BALANCE=ACC_BALANCE+OLD.FD_MATURITY_AMOUNT WHERE ACC_ID_PK=OLD.ACC_ID_FK;

INSERT INTO FD_HISTORY VALUES(OLD.FD_ID_PK,OLD.ACC_ID_FK,OLD.FD_START_DATE,OLD.FD_MATURITY_DATE,OLD.FD_AMOUNT,OLD.FD_MATURITY_AMOUNT,NOW(),0);
END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `fixed_deposit_details`
--

CREATE TABLE `fixed_deposit_details` (
  `MIN_FD_AMT` int(20) NOT NULL,
  `PENALTY` int(10) NOT NULL,
  `MIN_FD_PERIOD` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fixed_deposit_details`
--

INSERT INTO `fixed_deposit_details` (`MIN_FD_AMT`, `PENALTY`, `MIN_FD_PERIOD`) VALUES
(50000, 10, 365);

-- --------------------------------------------------------

--
-- Table structure for table `loan_details`
--

CREATE TABLE `loan_details` (
  `LOAN_TYPE_PK` varchar(50) NOT NULL,
  `LOAN_RATE` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_details`
--

INSERT INTO `loan_details` (`LOAN_TYPE_PK`, `LOAN_RATE`) VALUES
('BUSINESS', 5),
('EDUCATION', 5),
('GOLD', 3),
('HOME', 3),
('PERSONAL', 4),
('VEHICLE', 5);

-- --------------------------------------------------------

--
-- Table structure for table `loan_sanctions`
--

CREATE TABLE `loan_sanctions` (
  `ACC_ID_FK` int(10) NOT NULL,
  `I_LOAN_AMOUNT` int(20) NOT NULL,
  `L_START_DATE` date NOT NULL,
  `L_END_DATE` date NOT NULL,
  `UPDATE_DATE` date NOT NULL,
  `LOAN_TYPE_FK` varchar(30) NOT NULL,
  `P_LOAN_AMOUNT` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_sanctions`
--

INSERT INTO `loan_sanctions` (`ACC_ID_FK`, `I_LOAN_AMOUNT`, `L_START_DATE`, `L_END_DATE`, `UPDATE_DATE`, `LOAN_TYPE_FK`, `P_LOAN_AMOUNT`) VALUES
(7, 100000, '2017-11-13', '2019-10-11', '2017-11-13', 'BUSINESS', 105000),
(8, 100000, '2017-11-13', '2019-01-09', '2017-11-13', 'GOLD', 103000);

-- --------------------------------------------------------

--
-- Table structure for table `loan_transactions`
--

CREATE TABLE `loan_transactions` (
  `ACC_ID_FK` int(10) NOT NULL,
  `L_AMT_PAID` int(20) NOT NULL,
  `L_AMT_B` int(20) NOT NULL,
  `L_AMT_A` int(20) NOT NULL,
  `LT_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `ACC_ID_FK` int(10) NOT NULL,
  `T_ACC_TYPE` varchar(10) NOT NULL,
  `T_DATE` date NOT NULL,
  `T_AMOUNT` int(20) NOT NULL,
  `OLD_BALANCE` int(20) NOT NULL,
  `NEW_BALANCE` int(20) NOT NULL,
  `TRANS_TYPE` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`ACC_ID_FK`, `T_ACC_TYPE`, `T_DATE`, `T_AMOUNT`, `OLD_BALANCE`, `NEW_BALANCE`, `TRANS_TYPE`) VALUES
(1, 'SAVINGS', '2017-11-13', 500000, 0, 500000, 'DEPOSIT'),
(2, 'CURRENT', '2017-11-13', 100000, 0, 100000, 'DEPOSIT'),
(1, 'SAVINGS', '2017-11-13', 100000, 500000, 400000, 'FD'),
(2, 'CURRENT', '2017-11-13', 70000, 100000, 30000, 'FD'),
(1, 'SAVINGS', '2017-11-13', 10000, 400000, 410000, 'DEPOSIT'),
(1, 'SAVINGS', '2017-11-13', 10000, 410000, 400000, 'WITHDRAW'),
(1, 'SAVINGS', '2017-11-13', 10000, 400000, 390000, 'FD'),
(1, 'SAVINGS', '2017-11-13', 10000, 390000, 400000, 'FD BREAK'),
(1, 'SAVINGS', '2017-11-13', 100000, 551828, 451828, 'FD'),
(1, 'SAVINGS', '2017-11-13', 100000, 722544, 622544, 'FD'),
(1, 'SAVINGS', '2017-11-13', 100000, 680271, 580271, 'FD'),
(1, 'SAVINGS', '2017-11-13', 200000, 580271, 380271, 'FD'),
(10, 'CURRENT', '2017-11-13', 100000, 0, 100000, 'DEPOSIT'),
(1, 'SAVINGS', '2017-11-13', 99999, 380271, 280272, 'WITHDRAW'),
(1, 'SAVINGS', '2017-11-13', 100000, 280272, 180272, 'FD'),
(1, 'SAVINGS', '2017-11-13', 100000, 180272, 280272, 'FD BREAK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`ACC_ID_PK`),
  ADD KEY `BR_ID_FK` (`BR_ID_FK`),
  ADD KEY `CUST_ID` (`CUST_ID_FK`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`BR_ID_PK`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CUST_ID_PK`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EMP_ID_PK`),
  ADD KEY `BR_ID_FK` (`BR_ID_FK`);

--
-- Indexes for table `employee_login_details`
--
ALTER TABLE `employee_login_details`
  ADD KEY `EMP_ID_FK` (`EMP_ID_FK`);

--
-- Indexes for table `fixed_deposit_data`
--
ALTER TABLE `fixed_deposit_data`
  ADD PRIMARY KEY (`FD_ID_PK`),
  ADD KEY `ACC_ID_FK` (`ACC_ID_FK`);

--
-- Indexes for table `loan_details`
--
ALTER TABLE `loan_details`
  ADD PRIMARY KEY (`LOAN_TYPE_PK`);

--
-- Indexes for table `loan_sanctions`
--
ALTER TABLE `loan_sanctions`
  ADD KEY `ACC_ID_FK` (`ACC_ID_FK`),
  ADD KEY `LOAN_TYPE_FK` (`LOAN_TYPE_FK`);

--
-- Indexes for table `loan_transactions`
--
ALTER TABLE `loan_transactions`
  ADD KEY `ACC_ID_FK` (`ACC_ID_FK`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD KEY `ACC_ID_FK` (`ACC_ID_FK`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `ACC_ID_PK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `BR_ID_PK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CUST_ID_PK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EMP_ID_PK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fixed_deposit_data`
--
ALTER TABLE `fixed_deposit_data`
  MODIFY `FD_ID_PK` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`BR_ID_FK`) REFERENCES `branch` (`BR_ID_PK`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`CUST_ID_FK`) REFERENCES `customer` (`CUST_ID_PK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`BR_ID_FK`) REFERENCES `branch` (`BR_ID_PK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_login_details`
--
ALTER TABLE `employee_login_details`
  ADD CONSTRAINT `employee_login_details_ibfk_1` FOREIGN KEY (`EMP_ID_FK`) REFERENCES `employee` (`EMP_ID_PK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fixed_deposit_data`
--
ALTER TABLE `fixed_deposit_data`
  ADD CONSTRAINT `fixed_deposit_data_ibfk_1` FOREIGN KEY (`ACC_ID_FK`) REFERENCES `account` (`ACC_ID_PK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loan_sanctions`
--
ALTER TABLE `loan_sanctions`
  ADD CONSTRAINT `loan_sanctions_ibfk_1` FOREIGN KEY (`ACC_ID_FK`) REFERENCES `account` (`ACC_ID_PK`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_sanctions_ibfk_2` FOREIGN KEY (`LOAN_TYPE_FK`) REFERENCES `loan_details` (`LOAN_TYPE_PK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loan_transactions`
--
ALTER TABLE `loan_transactions`
  ADD CONSTRAINT `loan_transactions_ibfk_1` FOREIGN KEY (`ACC_ID_FK`) REFERENCES `loan_sanctions` (`ACC_ID_FK`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`ACC_ID_FK`) REFERENCES `account` (`ACC_ID_PK`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `L_AMT_UPDATE` ON SCHEDULE EVERY 1 MINUTE STARTS '2017-11-13 12:54:05' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

CALL UPDATE_L_AMT();

END$$

CREATE DEFINER=`root`@`localhost` EVENT `DELETE_FD` ON SCHEDULE EVERY 1 MINUTE STARTS '2017-11-13 16:46:11' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

CALL FD_DELETE();

END$$

CREATE DEFINER=`root`@`localhost` EVENT `FD_AMT_UPDATE` ON SCHEDULE EVERY 1 MINUTE STARTS '2017-11-13 12:54:27' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

CALL UPDATE_FD_AMT();

END$$

CREATE DEFINER=`root`@`localhost` EVENT `S_AMT_UPDATE` ON SCHEDULE EVERY 1 MINUTE STARTS '2017-11-13 16:45:53' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

CALL UPDATE_S_AMT();

END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
