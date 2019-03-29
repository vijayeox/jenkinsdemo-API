DROP TRIGGER IF EXISTS `before_insert_ox_user`;
DELIMITER $$
CREATE TRIGGER before_insert_ox_user 
BEFORE INSERT ON ox_user 
FOR EACH ROW
BEGIN
	SET NEW.name = CONCAT(NEW.firstname, ' ', NEW.lastname);
    IF(NEW.uuid IS NULL OR NEW.uuid = '') THEN
		SET NEW.uuid = uuid();
    END IF;
END
$$
DELIMITER ;