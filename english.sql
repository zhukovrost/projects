create or replace table questions
(
	id int auto_increment
		primary key,
	question longtext collate utf8mb4_bin not null
		check (json_valid(`question`)),
	theme text null
);

create or replace table report_files
(
	id int auto_increment
		primary key,
	file mediumblob not null
);

create or replace table reports
(
	id int auto_increment
		primary key,
	user varchar(32) not null,
	files longtext collate utf8mb4_bin default '[]' not null
		check (json_valid(`files`)),
	message text not null,
	rate int(1) not null,
	date varchar(10) not null
);

create or replace table test_images
(
	id int auto_increment
		primary key,
	image longblob not null
);

create or replace table tests
(
	id int auto_increment
		primary key,
	name text not null,
	test longtext collate utf8mb4_bin not null
		check (json_valid(`test`))
);

create or replace table themes
(
	theme text not null
);

create or replace table users
(
	id int auto_increment
		primary key,
	login varchar(32) not null,
	name varchar(32) not null,
	surname varchar(32) not null,
	thirdname varchar(32) null,
	email varchar(32) not null,
	password varchar(32) not null,
	status varchar(5) default 'user' not null,
	user_tests_ids longtext collate utf8mb4_bin default '[]' not null
		check (json_valid(`user_tests_ids`)),
	user_tests_marks longtext collate utf8mb4_bin default '[]' not null
		check (json_valid(`user_tests_marks`)),
	user_tests_durations longtext collate utf8mb4_bin default '[]' not null
		check (json_valid(`user_tests_durations`))
);

create or replace table verification_tests
(
	id int auto_increment
		primary key,
	login varchar(32) not null,
	test_id int not null,
	right_answers int not null,
	amount int not null,
	answers longtext collate utf8mb4_bin not null,
	answers_ids longtext collate utf8mb4_bin not null,
	position int not null
);

truncate users;
insert into users(login, name, surname, thirdname, email, password, status) VALUES ("admin", "admin", "admin", "admin", "admin@admin.com", "202cb962ac59075b964b07152d234b70", "admin");