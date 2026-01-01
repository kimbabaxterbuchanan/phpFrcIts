 SELECT  t0.last_name,t0.first_name,t0.expiration,t0.email,t0.member_id,t1.member_id  From member t0,

member_pass t1  Where t0.member_id = t1.member_id AND t0.member_id > 100 AND t0.member_id < 200     Group By t0.member_id   Order by t0.member_id  ASC