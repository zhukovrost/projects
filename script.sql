create database new_sport;

create table new_sport.avatars
(
    id   int auto_increment
        primary key,
    file longtext not null
);

# INSERT INTO new_sport.avatars (file) SELECT file FROM sport.avatars WHERE id = 1;

use new_sport;

create table users
(
    id                int auto_increment
        primary key,
    login             varchar(32)                       not null,
    status            varchar(8) default 'user'         not null,
    name              varchar(32)                       not null,
    surname           varchar(32)                       not null,
    description       text       default 'Нет описания' null,
    password          varchar(256)                      not null,
    avatar            int        default 1              null,
    type              int(1)     default 0              not null,
    preparation_level int(1)     default 0              not null,
    vk                varchar(64)                       null,
    tg                varchar(64)                       null,
    check (`status` in ('user', 'admin', 'coach', 'doctor'))
);

create table coach_advice
(
    id   int auto_increment
        primary key,
    user int           not null,
    name varchar(2048) not null,
    link varchar(512)  null,
    constraint coach_advice_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create table competitions
(
    id   int auto_increment
        primary key,
    user int          not null,
    name varchar(255) not null,
    date timestamp    null,
    link varchar(512) null,
    constraint competitions_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create table exercises
(
    id          int auto_increment
        primary key,
    creator     int          default 1  not null,
    name        varchar(64)             not null,
    static      tinyint(1)   default 0  null,
    description varchar(512) default '' null,
    muscles     varchar(6)              not null,
    difficulty  int(1)       default 3  not null,
    constraint exercises_ibfk_1
        foreign key (creator) references users (id)
            on update cascade on delete cascade,
    check (`muscles` in ('press', 'legs', 'arms', 'back', 'chest', 'cardio'))
);

create table exercise_ratings
(
    exercise int    not null,
    user     int    not null,
    rate     int(1) not null,
    primary key (exercise, user),
    constraint exercise_ratings_ibfk_1
        foreign key (exercise) references exercises (id)
            on update cascade on delete cascade,
    constraint exercise_ratings_ibfk_2
        foreign key (user) references users (id)
            on update cascade,
    check (1 <= `rate` <= 5)
);

create index user
    on exercise_ratings (user);

create index creator
    on exercises (creator);

create table goals
(
    id   int auto_increment
        primary key,
    user int                  not null,
    name varchar(255)         not null,
    done tinyint(1) default 0 not null,
    constraint goals_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create table medicines
(
    id      int auto_increment
        primary key,
    user    int          not null,
    name    varchar(255) not null,
    caption varchar(255) null,
    constraint medicines_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create table news
(
    id       int auto_increment
        primary key,
    message  text                                   not null,
    user     int                                    not null,
    date     timestamp  default current_timestamp() not null on update current_timestamp(),
    personal tinyint(1) default 0                   not null,
    constraint news_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create index user
    on news (user);

create table phys_updates
(
    user   int                                   not null,
    date   timestamp default current_timestamp() not null on update current_timestamp(),
    height int(3)                                not null,
    weight int(3)                                not null,
    constraint phys_updates_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create index user
    on phys_updates (user);

create table programs
(
    id      int auto_increment
        primary key,
    name    varchar(64) default '' not null,
    creator int                    not null,
    constraint programs_ibfk_1
        foreign key (creator) references users (id)
);

create table program_ratings
(
    program int not null,
    user    int not null,
    rate    int not null,
    primary key (program, user),
    constraint program_ratings_ibfk_1
        foreign key (program) references programs (id)
            on update cascade on delete cascade,
    constraint program_ratings_ibfk_2
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create index user
    on program_ratings (user);

create table program_to_user
(
    user       int              not null,
    program    int              not null,
    date_start date             null,
    weeks      int(3) default 1 not null,
    constraint program_to_user_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade,
    constraint program_to_user_ibfk_2
        foreign key (program) references programs (id)
            on update cascade on delete cascade
);

create index program
    on program_to_user (program);

create index creator
    on programs (creator);

create table recommendations
(
    user           int          not null
        primary key,
    recommendation varchar(512) null,
    constraint recommendations_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create table requests
(
    user     int not null,
    receiver int not null,
    primary key (user, receiver),
    constraint requests_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade,
    constraint requests_ibfk_2
        foreign key (receiver) references users (id)
            on update cascade on delete cascade
);

create index receiver
    on requests (receiver);

create table subs
(
    user       int not null,
    subscriber int not null,
    primary key (user, subscriber),
    constraint subs_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade,
    constraint subs_ibfk_2
        foreign key (subscriber) references users (id)
            on update cascade on delete cascade
);

create index subscriber
    on subs (subscriber);

create table treatment_period
(
    user         int       not null
        primary key,
    intake_start timestamp null,
    intake_end   timestamp null,
    constraint treatment_period_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create table user_added_exercises
(
    user_id     int not null,
    exercise_id int not null,
    primary key (user_id, exercise_id),
    constraint user_added_exercises_ibfk_1
        foreign key (user_id) references users (id)
            on update cascade on delete cascade,
    constraint user_added_exercises_ibfk_2
        foreign key (exercise_id) references exercises (id)
            on update cascade on delete cascade
);

create index exercise_id
    on user_added_exercises (exercise_id);

create table user_featured_exercises
(
    user_id     int not null,
    exercise_id int not null,
    primary key (user_id, exercise_id),
    constraint user_featured_exercises_ibfk_1
        foreign key (user_id) references users (id)
            on update cascade on delete cascade,
    constraint user_featured_exercises_ibfk_2
        foreign key (exercise_id) references exercises (id)
            on update cascade on delete cascade
);

create index exercise_id
    on user_featured_exercises (exercise_id);

create table user_to_coach
(
    user  int not null
        primary key,
    coach int not null,
    constraint user_to_coach_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade,
    constraint user_to_coach_ibfk_2
        foreign key (coach) references users (id)
            on update cascade on delete cascade
);

create index coach
    on user_to_coach (coach);

create table user_to_doctor
(
    user   int not null
        primary key,
    doctor int not null,
    constraint user_to_doctor_ibfk_1
        foreign key (user) references users (id)
            on update cascade on delete cascade,
    constraint user_to_doctor_ibfk_2
        foreign key (doctor) references users (id)
            on update cascade on delete cascade
);

create index doctor
    on user_to_doctor (doctor);

create index avatar
    on users (avatar);

create table workouts
(
    id      int auto_increment
        primary key,
    creator int                    not null,
    name    varchar(64) default '' not null,
    loops   int         default 1  not null,
    constraint workouts_ibfk_1
        foreign key (creator) references users (id)
            on update cascade
);

create table control_workouts
(
    id      int                                    not null
        primary key,
    user    int                                    not null,
    is_done tinyint(1) default 0                   not null,
    date    timestamp  default current_timestamp() not null on update current_timestamp(),
    constraint control_workouts_ibfk_1
        foreign key (id) references workouts (id)
            on update cascade on delete cascade,
    constraint control_workouts_ibfk_2
        foreign key (user) references users (id)
            on update cascade on delete cascade
);

create index user
    on control_workouts (user);

create table program_workouts
(
    program_id int    not null,
    workout_id int    not null,
    week_day   int(1) not null,
    constraint program_workouts_ibfk_1
        foreign key (program_id) references programs (id)
            on update cascade on delete cascade,
    constraint program_workouts_ibfk_2
        foreign key (workout_id) references workouts (id)
            on update cascade,
    check (`week_day` in (0, 1, 2, 3, 4, 5, 6))
);

create index program_id
    on program_workouts (program_id);

create index workout_id
    on program_workouts (workout_id);

create table user_featured_workouts
(
    user_id    int not null,
    workout_id int not null,
    primary key (user_id, workout_id),
    constraint user_featured_workouts_ibfk_1
        foreign key (user_id) references users (id)
            on update cascade on delete cascade,
    constraint user_featured_workouts_ibfk_2
        foreign key (workout_id) references workouts (id)
            on update cascade on delete cascade
);

create index workout_id
    on user_featured_workouts (workout_id);

create table workout_exercises
(
    workout_id  int    null,
    exercise_id int    null,
    creator     int    null,
    sets        int(2) null,
    reps        int(5) null,
    constraint workout_exercises_ibfk_1
        foreign key (workout_id) references workouts (id)
            on update cascade on delete cascade,
    constraint workout_exercises_ibfk_2
        foreign key (exercise_id) references exercises (id)
            on update cascade on delete cascade,
    constraint workout_exercises_ibfk_3
        foreign key (creator) references users (id)
            on update cascade
);

create index creator
    on workout_exercises (creator);

create index exercise_id
    on workout_exercises (exercise_id);

create index workout_id
    on workout_exercises (workout_id);

create table workout_history
(
    id             int auto_increment
        primary key,
    user           int                                   not null,
    workout        int                                   not null,
    date_completed timestamp default current_timestamp() not null on update current_timestamp(),
    time_spent     time                                  not null,
    constraint workout_history_ibfk_1
        foreign key (user) references users (id)
            on update cascade,
    constraint workout_history_ibfk_2
        foreign key (workout) references workouts (id)
            on update cascade
);

create index user
    on workout_history (user);

create index workout
    on workout_history (workout);

create index creator
    on workouts (creator);

create definer = root@localhost trigger set_workout_id_to_zero
    after delete
    on workouts
    for each row
BEGIN
    UPDATE program_workouts SET workout_id = 0 WHERE workout_id = OLD.id;
END;


