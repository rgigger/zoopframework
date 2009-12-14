begin;

create table entry
(
    "id" serial primary key,
    "start" timestamp with time zone,
    "end" timestamp with time zone,
    "is_duration" boolean,
    "title" text
);

commit;