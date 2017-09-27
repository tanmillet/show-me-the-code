Create Database Online;

use Online;

/*  Ver 1.0 */
CREATE  TABLE `online`.`contestant` (
  `ApplyID` VARCHAR(20) NOT NULL ,
  `Name` VARCHAR(30) NOT NULL ,
  `Grade` VARCHAR(20) NOT NULL ,
  `School` VARCHAR(100) NOT NULL ,
  `ScoreDetail` VARCHAR(500) NULL ,
  `PaperID` INT NULL ,
  `ContestTime` VARCHAR(10) NOT NULL,
  `Score` INT NULL,
  PRIMARY KEY (`ApplyID`) ,
  INDEX `PaperID` (`PaperID` ASC) ,
  CONSTRAINT `PaperID`
    FOREIGN KEY (`PaperID` )
    REFERENCES `online`.`contestpaper` (`PaperID` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION) ENGINE = MyISAM;


CREATE  TABLE `online`.`ContestPaper` (
  `PaperID` INT NOT NULL AUTO_INCREMENT,
  `ContestTime` VARCHAR(10) NOT NULL ,
  `QuestionTypeTemplate` VARCHAR(1000) NOT NULL ,
  `DomainTemplate` VARCHAR(500) NOT NULL ,
  `FullScore` INT NOT NULL ,
  `ForGrade` VARCHAR(20) NOT NULL,
  `Comment` VARCHAR(1000) NULL,
  PRIMARY KEY (`PaperID`) ) ENGINE = MyISAM;

CREATE  TABLE `online`.`StatScores` (
  `PaperID` INT NOT NULL ,
  `Region` VARCHAR(100) NOT NULL ,
  `ScoreDetail` VARCHAR(500) NOT NULL ,
  PRIMARY KEY (`PaperID`, `Region`) ) ENGINE = MyISAM;

  ALTER TABLE contestant ADD FULLTEXT(ApplyID, Name, Grade, School);


