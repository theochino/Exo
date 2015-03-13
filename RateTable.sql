-- Read the instruction
-- and it mention "daily" so it would seem that 
-- the historical info need to be kept.

-- In the code after getting the daily, I would either do a quick
-- SELECT * FROM RateRate WHERE RateRate_Date = NOW();
-- and if the data is different, update each one of the different table.
-- Or just run a delete on today and force feed the DB with the data.

CREATE TABLE RateName (
	RateName_ID int unsigned auto_increment primary key,
	RateName_CountryUsed varchar(100),
	RateName_CountryCode char(3)
);

-- It's a bit useless on 180 countries but idx space is not that
-- expensive.
CREATE INDEX RateName_CountryCode_IDX ON RateName (RateName_CountryCode);

-- This to showcase the fact that I know how to insert stuff in the DB.
INSERT INTO RateName (RateName_CountryUsed, RateName_CountryCode) VALUES ("Japan", "JPY");
INSERT INTO RateName (RateName_CountryUsed, RateName_CountryCode) VALUES ("Bulgaria", "BGN"), ("Czech Republic", "CZK");
INSERT INTO RateName SET RateName_CountryUsed = "Argentina", RateName_CountryCode = "ARS";
INSERT INTO RateName SET RateName_CountryCode = "AUD";
UPDATE RateName SET RateName_CountryUsed = "Australia" WHERE RateName_CountryCode = "AUD";


CREATE TABLE RateRate (
	RateName_ID int unsigned,
	RateRate_Value decimal (10,6),
	RateRate_Date date
);


-- This way to show case how to initialize a relational DB from an excel spreadsheet
INSERT INTO RateRate (RateName_ID, RateRate_Value, RateRate_Date) SELECT RateName_ID, "0.013125", NOW() FROM RateName WHERE RateName_CountryCode = "JPY";
INSERT INTO RateRate (RateName_ID, RateRate_Value, RateRate_Date) SELECT RateName_ID, "0.6707", NOW() FROM RateName WHERE RateName_CountryCode = "BGN";
INSERT INTO RateRate (RateName_ID, RateRate_Value, RateRate_Date) SELECT RateName_ID, "0.05190", NOW() FROM RateName WHERE RateName_CountryCode = "CZK";
INSERT INTO RateRate (RateName_ID, RateRate_Value, RateRate_Date) SELECT RateName_ID, "0.2294", NOW() FROM RateName WHERE RateName_CountryCode = "ARS";
INSERT INTO RateRate (RateName_ID, RateRate_Value, RateRate_Date) SELECT RateName_ID, "1.0689", NOW() FROM RateName WHERE RateName_CountryCode = "AUD";
INSERT INTO RateRate (RateName_ID, RateRate_Value, RateRate_Date) SELECT RateName_ID, "1.1154", NOW() FROM RateName WHERE RateName_CountryCode = "CHF";
