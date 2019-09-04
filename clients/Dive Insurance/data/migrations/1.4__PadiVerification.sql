CREATE DEFINER=`localhost`@`%` PROCEDURE `ox_padi_verification`(
_a varchar(100),
_b varchar(100),
_c varchar(100),
_d varchar(100),
_e varchar(100),
_f varchar(100),
_g varchar(100),
_h varchar(100),
_i varchar(100),
_j varchar(100),
_k varchar(100),
_l varchar(100),
_m varchar(100),
_n varchar(100),
_o varchar(100),
_p varchar(100),
_q varchar(100),
_r varchar(100))
BEGIN
	
	IF EXISTS (select * from `ox_padi_verification_pl` where member_number = _a) THEN
	    update `ox_padi_verification_pl` set 
		   `member_number` = _a,
	  		`first_name` = _b,
	  		`MI` = _c,
	  		`last_name` = _d,
	  		`address_1` = _e,
	  		`address_2` = _f,
	  		`address_international` = _g,
	  		`city` = _h,
	  		`state` = _i,
	  		`zip` = _j,
	  		`country_code` = _k,
	  		`home_phone` = _l,
	  		`work_phone` = _m,
	  		`insurance_type` = _n,
	  		`date_expire` = _o,
	  		`rating` = _p,
	  		`email` = _q,
	  		`num` = _r
   where member_number = _a;
	  ELSE 
	    INSERT INTO ox_padi_verification_pl (`member_number`, `first_name`, `MI`, `last_name`, `address_1`, `address_2`, `address_international`, `city`, `state`, `zip`, `country_code`, `home_phone`, `work_phone`, `insurance_type`, `date_expire`, `rating`, `email`, `num`) 
   VALUES (_a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l, _m, _n, _o, _p, _q, _r);
	  END IF;
 
END