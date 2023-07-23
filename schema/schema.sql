CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE TABLE users (
  ID uuid DEFAULT uuid_generate_v4(),
  username VARCHAR(25) NOT NULL UNIQUE,
  display_name VARCHAR(35) NOT NULL,
  photo_url VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE,
  provider VARCHAR(50) NOT NULL,
  provider_id INT NOT NULL,
  PRIMARY KEY(ID)
);
