# IntelliDocM Database Schema Documentation

## Overview
This document provides a comprehensive overview of the database schema for the IntelliDocM project, which is a club activity and facility management system for educational institutions.

## Database Information
- **Database Name**: `ddbdb` (from database.php)
- **Engine**: MySQL/MariaDB
- **Character Set**: UTF-8
- **Collation**: utf8mb4_general_ci

## Current Schema Issues Identified

### 1. Inconsistent Table Definitions
The project has multiple SQL files with conflicting table definitions:
- `database.sql` - Main schema file
- `database/create_notifications_table.sql` - Conflicting notifications table
- `database/notifications.sql` - Another conflicting notifications table
- `database/activity_log.sql` - Conflicting activity_log table

### 2. Missing Table Definition
The `rooms` table is referenced throughout the codebase but not defined in the main schema file.

## Updated and Corrected Database Schema

### Core Tables

#### 1. Users Table
```sql
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role ENUM('admin', 'moderator', 'dean', 'client') NOT NULL,
    contact VARCHAR(55) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### 2. Clubs Table
```sql
CREATE TABLE clubs (
    club_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    club_name VARCHAR(255) NOT NULL,
    acronym VARCHAR(11) NOT NULL UNIQUE,
    club_type VARCHAR(255) NOT NULL,
    moderator VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### 3. Club Memberships Table
```sql
CREATE TABLE club_memberships (
    membership_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    club_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    designation ENUM('president', 'vice_president', 'secretary', 'treasurer', 'member', 'dean') NOT NULL,
    joined_date DATE DEFAULT CURRENT_DATE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (club_id) REFERENCES clubs(club_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_membership (club_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Activity Management Tables

#### 4. Activity Proposals Table
```sql
CREATE TABLE activity_proposals (
    proposal_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    club_name VARCHAR(255) DEFAULT NULL,
    acronym VARCHAR(50) DEFAULT NULL,
    club_type VARCHAR(50) DEFAULT NULL,
    designation VARCHAR(255) DEFAULT NULL,
    activity_title VARCHAR(255) NOT NULL,
    activity_type VARCHAR(55) NOT NULL,
    objectives TEXT NOT NULL,
    program_category VARCHAR(255) NOT NULL,
    venue VARCHAR(255) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    activity_date DATE NOT NULL,
    end_activity_date DATE DEFAULT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    target_participants VARCHAR(255) DEFAULT NULL,
    expected_participants INT(11) DEFAULT NULL,
    applicant_name VARCHAR(255) DEFAULT NULL,
    applicant_signature BLOB DEFAULT NULL,
    applicant_designation VARCHAR(255) DEFAULT NULL,
    applicant_date_filed DATE DEFAULT NULL,
    applicant_contact VARCHAR(50) DEFAULT NULL,
    moderator_name VARCHAR(255) DEFAULT NULL,
    moderator_signature BLOB DEFAULT NULL,
    moderator_date_signed DATE DEFAULT NULL,
    moderator_contact VARCHAR(50) DEFAULT NULL,
    faculty_signature VARCHAR(255) DEFAULT NULL,
    faculty_contact VARCHAR(50) DEFAULT NULL,
    dean_name VARCHAR(255) DEFAULT NULL,
    dean_signature BLOB DEFAULT NULL,
    dean_date_signed DATE DEFAULT NULL,
    status ENUM('Received', 'Pending', 'Confirmed', 'Rejected') DEFAULT 'Received',
    rejection_reason TEXT DEFAULT NULL,
    submitted_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Facility Management Tables

#### 5. Facilities Table
```sql
CREATE TABLE facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    capacity INT DEFAULT 0,
    status ENUM('active', 'maintenance', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### 6. Rooms Table (Missing from original schema)
```sql
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id INT NOT NULL,
    room_number VARCHAR(50) NOT NULL,
    capacity INT DEFAULT 30,
    description TEXT,
    status ENUM('available', 'maintenance', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_room (facility_id, room_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### 7. Facility Availability Table
```sql
CREATE TABLE facility_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('available', 'blocked', 'unavailable', 'maintenance') NOT NULL,
    reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_availability (facility_id, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Booking Management Tables

#### 8. Bookings Table
```sql
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id INT NOT NULL,
    user_id INT NOT NULL,
    activity_proposal_id INT DEFAULT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Cancelled', 'Completed') DEFAULT 'Pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (activity_proposal_id) REFERENCES activity_proposals(proposal_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### 9. Booking Rooms Table
```sql
CREATE TABLE booking_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    room_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_booking_room (booking_id, room_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### 10. Block Requests Table
```sql
CREATE TABLE block_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    club_id INT NOT NULL,
    facility_id INT NOT NULL,
    date DATE NOT NULL,
    start_time TIME DEFAULT '00:00:00',
    end_time TIME DEFAULT '23:59:59',
    reason TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    requested_by INT NOT NULL,
    approved_by INT DEFAULT NULL,
    approved_at TIMESTAMP NULL,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (club_id) REFERENCES clubs(club_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Notification and Communication Tables

#### 11. Notifications Table (Consolidated)
```sql
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    proposal_id INT DEFAULT NULL,
    booking_id INT DEFAULT NULL,
    notification_type ENUM('proposal', 'booking', 'system', 'reminder') DEFAULT 'system',
    is_read TINYINT(1) DEFAULT 0,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (proposal_id) REFERENCES activity_proposals(proposal_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Event Management Tables

#### 12. Events Table
```sql
CREATE TABLE events (
    event_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_title VARCHAR(255) NOT NULL,
    event_description TEXT,
    event_date DATE NOT NULL,
    event_start_date DATE DEFAULT NULL,
    event_end_date DATE DEFAULT NULL,
    start_time TIME DEFAULT NULL,
    end_time TIME DEFAULT NULL,
    venue VARCHAR(255) DEFAULT NULL,
    organizer_id INT DEFAULT NULL,
    status ENUM('draft', 'published', 'cancelled') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Audit and Logging Tables

#### 13. Activity Log Table (Consolidated)
```sql
CREATE TABLE activity_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    username VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    activity_type VARCHAR(50) NOT NULL,
    description TEXT,
    user_agent TEXT,
    activity_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Entity Relationship Diagram (ERD)

```
┌─────────────────┐         ┌─────────────────────┐         ┌─────────────────┐
│      users      │         │  club_memberships   │         │      clubs      │
├─────────────────┤         ├─────────────────────┤         ├─────────────────┤
│ id (PK)         │◄────────┤ membership_id (PK)  │────────►│ club_id (PK)    │
│ full_name       │         │ club_id (FK)        │         │ club_name       │
│ username        │         │ user_id (FK)        │         │ acronym         │
│ password        │         │ designation         │         │ club_type       │
│ email           │         │ status              │         │ moderator       │
│ role            │         └─────────────────────┘         └─────────────────┘
│ contact         │
└─────────────────┘
         │
         │
         ▼
┌─────────────────┐         ┌─────────────────────┐         ┌─────────────────┐
│activity_proposals│         │     bookings        │         │   facilities    │
├─────────────────┤         ├─────────────────────┤         ├─────────────────┤
│ proposal_id (PK)│◄────────┤ id (PK)             │────────►│ id (PK)         │
│ user_id (FK)    │         │ facility_id (FK)    │         │ name            │
│ activity_title  │         │ user_id (FK)        │         │ code            │
│ activity_type   │         │ proposal_id (FK)    │         │ description     │
│ objectives      │         │ booking_date        │         │ capacity        │
│ status          │         │ start_time          │         │ status          │
└─────────────────┘         │ end_time            │         └─────────────────┘
         │                  │ status              │                  │
         │                  └─────────────────────┘                  │
         │                           │                               │
         │                           ▼                               ▼
         │                  ┌─────────────────┐         ┌─────────────────┐
         │                  │  booking_rooms  │         │      rooms      │
         │                  ├─────────────────┤         ├─────────────────┤
         │                  │ id (PK)         │────────►│ id (PK)         │
         │                  │ booking_id (FK) │         │ facility_id (FK)│
         │                  │ room_id (FK)    │         │ room_number     │
         │                  └─────────────────┘         │ capacity        │
         │                                                │ description     │
         ▼                                                └─────────────────┘
┌─────────────────┐
│ notifications   │
├─────────────────┤
│ id (PK)         │
│ user_id (FK)    │
│ title           │
│ message         │
│ proposal_id (FK)│
│ is_read         │
└─────────────────┘
```

## Key Relationships

### 1. User Management
- **users** ↔ **club_memberships**: One user can belong to multiple clubs
- **users** ↔ **activity_proposals**: Users can submit multiple activity proposals
- **users** ↔ **bookings**: Users can make multiple facility bookings

### 2. Club Management
- **clubs** ↔ **club_memberships**: One club can have multiple members
- **clubs** ↔ **block_requests**: Clubs can request facility blocks

### 3. Facility Management
- **facilities** ↔ **rooms**: One facility can have multiple rooms
- **facilities** ↔ **bookings**: Facilities can have multiple bookings
- **facilities** ↔ **facility_availability**: Facilities have availability schedules

### 4. Activity Management
- **activity_proposals** ↔ **bookings**: Activity proposals can generate facility bookings
- **activity_proposals** ↔ **notifications**: Users receive notifications about proposal status

### 5. Booking Management
- **bookings** ↔ **booking_rooms**: Bookings can include specific rooms
- **bookings** ↔ **notifications**: Users receive notifications about booking status

## Recommended Improvements

### 1. Data Consistency
- Standardize ENUM values across all tables
- Add proper constraints and validation rules
- Implement consistent naming conventions

### 2. Performance Optimization
- Add appropriate indexes on frequently queried columns
- Consider partitioning for large tables (activity_log, notifications)
- Implement proper foreign key constraints with appropriate actions

### 3. Security Enhancements
- Encrypt sensitive data (signatures, personal information)
- Implement row-level security for multi-tenant scenarios
- Add audit trails for critical operations

### 4. Scalability
- Consider implementing soft deletes instead of hard deletes
- Add versioning for critical tables
- Implement proper archiving strategies for historical data

## Migration Scripts

### 1. Create Missing Tables
```sql
-- Create rooms table if it doesn't exist
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id INT NOT NULL,
    room_number VARCHAR(50) NOT NULL,
    capacity INT DEFAULT 30,
    description TEXT,
    status ENUM('available', 'maintenance', 'unavailable') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY unique_room (facility_id, room_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### 2. Update Existing Tables
```sql
-- Add missing columns to existing tables
ALTER TABLE activity_proposals 
ADD COLUMN end_activity_date DATE DEFAULT NULL AFTER activity_date,
ADD COLUMN dean_date_signed DATE DEFAULT NULL AFTER dean_signature,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update foreign key constraints
ALTER TABLE bookings 
ADD COLUMN activity_proposal_id INT DEFAULT NULL AFTER user_id,
ADD CONSTRAINT fk_bookings_proposal 
FOREIGN KEY (activity_proposal_id) REFERENCES activity_proposals(proposal_id) 
ON DELETE SET NULL ON UPDATE CASCADE;
```

## Conclusion

This updated schema addresses the inconsistencies found in the current codebase and provides a more robust foundation for the IntelliDocM system. The ERD clearly shows the relationships between entities, and the recommended improvements will enhance performance, security, and maintainability.

For implementation, it's recommended to:
1. Review and approve the schema changes
2. Create a migration plan to update existing databases
3. Test the new schema with existing application code
4. Update application code to use the new schema structure
5. Implement the recommended improvements incrementally
