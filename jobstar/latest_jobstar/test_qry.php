Select temp.* from (select jobs.id as jid,job_status.*,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users left join kids on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id  left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id left join job_status on job_status.job_id=jobs.id 
left join comments on comments.job_id=jobs.id left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=75 GROUP BY job_status.job_id,job_status.day
UNION  
select jobs.id as jid,job_status.*,(select count(likes.id) from likes where likes.job_id=jobs.id and likes.type='like') as likes_count,(select count(comments.id) from comments WHERE comments.job_id=jobs.id) as comment_count from users left join kids  on kids.user_id=users.id left join grownup_alias on grownup_alias.user_id=users.id  left join jobs on jobs.user_id=users.id and jobs.kid_id=kids.id left join job_status on job_status.job_id=jobs.id left join comments on comments.job_id=jobs.id left join likes on likes.job_id=jobs.id
left join users as U on U.id=comments.user_id
left join users as U1 on U1.id=likes.user_id
where users.id=75  AND jobs.repeat_job=1 GROUP BY job_status.job_id,job_status.day)as temp 