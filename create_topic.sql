CREATE TABLE IF NOT EXISTS topic (
  topic_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  chapter_id int(11) NOT NULL,
  course_id int(11) NOT NULL,
  title varchar(1023) NOT NULL,
  created_at DATE NOT NULL DEFAULT CURDATE()
);

