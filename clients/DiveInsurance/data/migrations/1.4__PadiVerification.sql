DROP PROCEDURE IF EXISTS ox_padi_verification;
CREATE PROCEDURE `ox_padi_verification`(
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
	
	IF EXISTS (select * from `padi_data` where member_number = _a) THEN
	    update `padi_data` set 
		    `member_number` = _a,
	  		`firstname` = _b,
	  		`MI` = _c,
	  		`lastname` = _d,
	  		`address1` = _e,
	  		`address2` = _f,
	  		`address_international` = _g,
	  		`city` = _h,
	  		`state` = _i,
	  		`zip` = _j,
	  		`country_code` = _k,
	  		`home_phone` = _l,
	  		`work_phone` = _m,
	  		`insurance_type` = _n,
	  		`date_expire` = FROM_UNIXTIME(_o),
	  		`rating` = _p,
	  		`email` = _q,
	  		`num` = _r
   where member_number = _a;
	  ELSE 
	    INSERT INTO padi_data (`member_number`, `firstname`, `MI`, `lastname`, `address1`, `address2`, `address_international`, `city`, `state`, `zip`, `country_code`, `home_phone`, `work_phone`, `insurance_type`, `date_expire`, `rating`, `email`, `num`) 
   VALUES (_a, _b, _c, _d, _e, _f, _g, _h, _i, _j, _k, _l, _m, _n,FROM_UNIXTIME(_o), _p, _q, _r);
	  END IF;
 
END