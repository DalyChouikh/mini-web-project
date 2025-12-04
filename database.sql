-- Blog Database Schema
-- This file is automatically executed when MySQL container starts

USE blog_db;

-- =====================================================
-- ARTICLES TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    summary TEXT,
    content TEXT NOT NULL,
    author VARCHAR(100) DEFAULT 'Anonymous',
    image_url VARCHAR(500),
    tags VARCHAR(255),
    read_time INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- COMMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    author VARCHAR(100) DEFAULT 'Anonymous',
    content TEXT NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ADMINS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DEFAULT ADMIN
-- Username: admin | Password: admin123
-- =====================================================
INSERT INTO admins (username, password_hash) VALUES 
('admin', '$2y$10$4s2zHWyKRpMjyiuEdKZ4A.8PcGVbWgh/1HO00VXmCUFh0TJHMFXNy');

-- =====================================================
-- INSERT SAMPLE ARTICLES
-- =====================================================
INSERT INTO articles (title, summary, content, author, image_url, tags, read_time, created_at) VALUES 
(
    'Designing a Minimalist Study Desk',
    'A clear, simple desk can help you focus longer and feel less stressed.',
    'A minimalist study desk is not about having nothing on it. It is about keeping only what actually supports your work. Start by removing every item you do not use daily. Then put back your laptop, a notebook, a pen, and one small personal object you like. Keep cables hidden as much as possible, and avoid stacking books you are not actively reading. The goal is that when you sit down, you know exactly what you are here to do. Over time, you can refine your setup, but the first big win is simply having fewer distractions in front of you.',
    'Daly Ch.',
    'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800&h=600&fit=crop',
    'minimalism,productivity,study',
    5,
    '2025-10-20 09:00:00'
),
(
    'How to Take Smart Digital Notes',
    'Stop rewriting your lectures and start building a useful knowledge base.',
    'Digital notes work best when they are short, connected, and easy to search. Instead of typing everything your teacher says, capture only key ideas, definitions, and examples. After class, spend ten minutes cleaning your notes: add headings, fix spelling, and create simple links or tags. Over time this turns into a personal knowledge base you can reuse for projects and exams. The important part is not the tool but the habit. Choose one app you like, keep the structure consistent, and review a few old notes each week.',
    'Daly Ch.',
    'https://images.unsplash.com/photo-1516534775068-ba3e7458af70?w=800&h=600&fit=crop',
    'productivity,notes,university',
    6,
    '2025-10-25 14:30:00'
),
(
    'Balancing Coding Projects and Exams',
    'A simple system to keep learning to code without failing your classes.',
    'It is easy to spend all your energy on side projects and forget about exams, or the other way around. A practical approach is to split your week into focus blocks. Reserve specific evenings for coding projects and keep them protected, just like a class. On other days, commit to exam preparation only. When you work on a project, define one clear outcome for the session, such as implementing one feature or fixing two bugs. When you study, define one chapter or topic. This way you make progress in both areas without burning out.',
    'Daly Ch.',
    'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&h=600&fit=crop',
    'coding,university,planning',
    7,
    '2025-11-01 11:15:00'
);

-- =====================================================
-- INSERT SAMPLE COMMENTS (some approved, some pending)
-- =====================================================
INSERT INTO comments (article_id, author, content, is_approved, created_at) VALUES
(1, 'Marie', 'Great tips! I cleaned my desk and I can already feel the difference.', TRUE, '2025-10-21 10:30:00'),
(1, 'Ahmed', 'Minimalism is really helping me focus better.', TRUE, '2025-10-22 15:45:00'),
(2, 'Sophie', 'I started using tags in my notes and it changed everything!', TRUE, '2025-10-26 09:20:00'),
(3, 'Lucas', 'This is exactly what I needed to read before finals.', FALSE, '2025-11-02 18:00:00'),
(3, 'Emma', 'Great advice for students!', FALSE, '2025-11-03 12:30:00');
