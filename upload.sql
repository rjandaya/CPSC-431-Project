create table upload (
    FileName    varchar(50) primary key,
    FileType    varchar(3),
    DateAdded   date,
    Size        float(4,2)
)