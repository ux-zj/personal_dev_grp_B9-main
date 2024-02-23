SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `tournament_y2`
--

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
                         `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         `username` varchar(32) DEFAULT NULL,
                         `password` varchar(64) DEFAULT NULL,
                         `email` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
                        (1, 'organicrabbit57', 'rosemaryssafsdfsdfsd', 'felix.thomas@example.com'),
                        (2, 'silverpanda145', 'something', 'changedss.mathew.carter@example.comaaa'),
                        (3, 'angrycat29', 'amber1', 'larissa.souza@example.com'),
                        (4, 'heavycat533', 'golden', 'ceylan.akyuz@example.com'),
                        (5, 'silverkoala474', 'forest', 'justin.anderson@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
                          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                          `title` varchar(20) NOT NULL UNIQUE,
                          `date_added` date NOT NULL,
                          `img` varchar(30) DEFAULT ('placeholder.png')

) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `title`, `date_added`, `img`) VALUES
                          (1, 'Call of Duty: Warzone', '2022-04-21', null),
                          (2, 'Call of Duty: Black Ops', '2022-04-21', 'blacops.png'),
                          (3, 'FIFA 2022', '2022-04-21', null);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
                           `id` int(5) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                           `owner` int(11) DEFAULT NULL,
                           `title` varchar(120) DEFAULT NULL,
                           `private` BOOL DEFAULT FALSE,
                           `date` datetime DEFAULT NULL,
                           `participants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`participants`)),
                           `ended` BOOL DEFAULT FALSE,

                            CONSTRAINT FK_owner FOREIGN KEY (owner) REFERENCES users(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `owner`, `title`, `private`, `date`, `ended`) VALUES
                           (1, 1, 'The animal Run', FALSE, '2022-03-27 22:13:44', TRUE),
                           (2, 1, 'Elements', FALSE, '2022-03-25 22:18:21', FALSE);
-- --------------------------------------------------------

--
-- Table structure for table `stages`
--

CREATE TABLE `stages` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `tournament_id` int(5) NOT NULL,
                          `activity_id` int(11) NOT NULL,
                          `format` varchar(20) NOT NULL,
                          `matches` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`matches`)),
                          `team_size` varchar(255) DEFAULT 1,

                          PRIMARY KEY (`id`, `tournament_id`),

                          CONSTRAINT CHECK_format CHECK ( format IN ('FFA', 'Gauntlet', 'Round Robin') ),
                          CONSTRAINT FK_activity FOREIGN KEY (activity_id) REFERENCES activities(id),
                          CONSTRAINT FK_tournament_id FOREIGN KEY (tournament_id) REFERENCES tournaments(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `stages`
--

INSERT INTO `stages` VALUES
    (1, 1, 1, 'FFA', '[]', NULL);

-- --------------------------------------------------------

COMMIT;
