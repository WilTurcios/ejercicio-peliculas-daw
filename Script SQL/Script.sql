create database movies_system;
use movies_system;

CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    original_title VARCHAR(255),
    year INT,
    duration INT,
    synopsis TEXT,
    director VARCHAR(255),
    writers VARCHAR(255),
    image varchar(255)
);

create table categories(
	id INT auto_increment primary key,
	category_name varchar(100) not null
);

select * from movies m ;
delete from movies m;

delete from categories where category_name = 'Acción';

CREATE TABLE movies_categories (
    movie_id INT REFERENCES movies(id) ON DELETE CASCADE ON UPDATE CASCADE,
    category_id INT REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (movie_id, category_id)
);

drop table movies  ;
