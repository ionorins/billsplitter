DROP TABLE Users;
CREATE TABLE Users (
    email TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    password TEXT NOT NULL,
    salt TEXT NOT NULL,
    sessionId TEXT
);

DROP TABLE Groups;
CREATE TABLE Groups (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    leader TEXT NOT NULL
);

DROP TABLE Bills;
CREATE TABLE Bills (
    id TEXT PRIMARY KEY,
    payee TEXT NOT NULL,
    payer TEXT NOT NULL,
    ammount FLOAT NOT NULL,
    confirmedPayee INTEGER NOT NULL,
    confirmedPayer INTEGER NOT NULL
);

DROP TABLE Memberships;
CREATE TABLE Memberships (
    id TEXT PRIMARY KEY,
    groupId TEXT NOT NULL,
    email TEXT NOT NULL
);

DROP TABLE Requests;
CREATE TABLE Requests (
    id TEXT PRIMARY KEY,
    groupId TEXT NOT NULL,
    email TEXT NOT NULL
);
