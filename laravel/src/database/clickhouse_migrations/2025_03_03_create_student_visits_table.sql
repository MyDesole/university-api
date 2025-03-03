CREATE TABLE IF NOT EXISTS student_visits (
                                              student_id UInt64,
                                              university_id UInt64,
                                              created_at DateTime DEFAULT now()
    ) ENGINE = MergeTree();
