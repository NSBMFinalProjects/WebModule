CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

CREATE TABLE users (
  ID uuid DEFAULT uuid_generate_v4(),
  username VARCHAR(25) NOT NULL UNIQUE,
  display_name VARCHAR(35) NOT NULL,
  photo_url VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE,
  provider VARCHAR(50) NOT NULL,
  provider_id INT NOT NULL,
  joined_at TIMESTAMP NOT NULL DEFAULT NOW(),
  PRIMARY KEY(ID)
);

CREATE TABLE profiles (
  ID uuid DEFAULT uuid_generate_v4(),
  user_id uuid NOT NULL UNIQUE,
  attempted INT NOT NULL DEFAULT 0,
  correct INT NOT NULL DEFAULT 0,
  PRIMARY KEY (ID),
  FOREIGN KEY (user_id) REFERENCES users(ID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE questions (
  ID uuid DEFAULT uuid_generate_v4(),
  doc_id VARCHAR(30) NOT NULL UNIQUE,
  attempts INT NOT NULL DEFAULT 0,
  correct INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT NOW(),
  displayed BOOLEAN NOT NULL DEFAULT false,
  PRIMARY KEY (ID)
);

CREATE TABLE answers (
  ID uuid DEFAULT uuid_generate_v4(),
  user_id uuid NOT NULL,
  question_id uuid NOT NULL,
  answer INT NOT NULL,
  answer_delay INT NOT NULL,
  answered_at TIMESTAMP NOT NULL DEFAULT now(),
  PRIMARY KEY (ID),
  FOREIGN KEY (user_id) REFERENCES users(ID) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (question_id) REFERENCES questions(ID) ON DELETE CASCADE ON UPDATE CASCADE
);
