CREATE database db_tran_thi_hong_nhung;
use db_tran_thi_hong_nhung;
CREATE TABLE  Course (
        id INT AUTO_INCREMENT PRIMARY KEY,
        Title VARCHAR(255) UNIQUE,
        Description TEXT,
        ImageUrl VARCHAR(255)
    )