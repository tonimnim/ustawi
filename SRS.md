# Software Requirements Specification (SRS)
## Ustawi Wa Jamii Website

### Document Version: 1.0
### Date: January 2025
### Prepared for: Ustawi Wa Jamii Organization

---

## Table of Contents
1. [Introduction](#1-introduction)
2. [Overall Description](#2-overall-description)
3. [System Features](#3-system-features)
4. [External Interface Requirements](#4-external-interface-requirements)
5. [Non-Functional Requirements](#5-non-functional-requirements)
6. [Other Requirements](#6-other-requirements)

---

## 1. Introduction

### 1.1 Purpose
This Software Requirements Specification (SRS) document describes the functional and non-functional requirements for the Ustawi Wa Jamii website. The website will serve as the primary digital platform for this youth-led Public Benefit Organization to showcase their work, accept donations, recruit volunteers, and engage with stakeholders.

### 1.2 Scope
The Ustawi Wa Jamii website will be a comprehensive web application built using Laravel (backend) and Vue.js (frontend) that includes:
- Public information portal
- Donation management system
- Career/volunteer portal
- Content management system
- Administrative dashboard
- Integration with local payment systems (M-Pesa)

### 1.3 Definitions, Acronyms, and Abbreviations
- **CMS**: Content Management System
- **API**: Application Programming Interface
- **M-Pesa**: Mobile money payment service in Kenya
- **STK Push**: SIM Toolkit Push (M-Pesa payment initiation)
- **WYSIWYG**: What You See Is What You Get
- **SRS**: Software Requirements Specification
- **NGO**: Non-Governmental Organization
- **CPC**: County Project Coordinator

### 1.4 References
- Laravel Documentation
- Vue.js Documentation
- M-Pesa API Documentation
- PayPal API Documentation

---

## 2. Overall Description

### 2.1 Product Perspective
The website will be a standalone system that interfaces with:
- Payment gateways (M-Pesa, PayPal, Credit Card processors)
- Email service providers
- Social media platforms
- Analytics services
- SMS gateways for notifications

### 2.2 Product Functions
Primary functions include:
- Information dissemination about projects and impact
- Online donation processing
- Volunteer and job application management
- Content management for administrators
- Donor relationship management
- Analytics and reporting

### 2.3 User Classes and Characteristics

#### 2.3.1 Public Visitors
- General public seeking information about the organization
- No technical expertise required
- Access via various devices (mobile, tablet, desktop)

#### 2.3.2 Donors
- Individuals or organizations making financial contributions
- Require secure payment processing
- Need donation history and tax receipts

#### 2.3.3 Job Seekers/Volunteers
- Individuals applying for positions
- Need to upload documents and track applications

#### 2.3.4 Content Administrators
- Staff members managing website content
- Basic computer literacy
- No coding knowledge required

#### 2.3.5 System Administrators
- Technical staff managing the system
- Full access to all features
- Technical expertise required

### 2.4 Operating Environment
- **Hosting**: Cloud-based hosting (AWS, DigitalOcean, or similar)
- **Database**: MySQL 8.0+
- **Server**: PHP 8.2+, Node.js 18+
- **Client Browsers**: Chrome, Firefox, Safari, Edge (latest 2 versions)
- **Mobile**: Responsive design for all screen sizes

### 2.5 Design and Implementation Constraints
- Must comply with Kenyan data protection laws
- Payment processing must be PCI compliant
- Website must be accessible (WCAG 2.1 AA)
- Must support both English and Swahili languages
- Budget constraints typical of NGO projects

---

## 3. System Features

### 3.1 Public Website Features

#### 3.1.1 Homepage
**Description**: The landing page that introduces visitors to Ustawi Wa Jamii

**Functional Requirements**:
- FR1.1.1: Display hero section with organization's key message
- FR1.1.2: Show featured projects in card/tile format
- FR1.1.3: Display animated impact counters (lives impacted, farmers trained, etc.)
- FR1.1.4: Provide prominent "Donate Now" call-to-action
- FR1.1.5: Show latest news/blog posts (3-5 items)
- FR1.1.6: Include newsletter subscription form
- FR1.1.7: Display social media links

#### 3.1.2 About Us Section
**Description**: Comprehensive information about the organization

**Functional Requirements**:
- FR1.2.1: Display mission, vision, and objectives
- FR1.2.2: Show organization history/timeline
- FR1.2.3: Display team members with photos and bios
- FR1.2.4: Show Board of Management information
- FR1.2.5: List County Project Coordinators with contact details
- FR1.2.6: Provide downloadable documents (annual reports, registration certificates)

#### 3.1.3 Projects/Programs Module
**Description**: Detailed information about all 8 organizational initiatives

**Functional Requirements**:
- FR1.3.1: Display projects overview page with all initiatives
- FR1.3.2: Provide individual detailed page for each project
- FR1.3.3: Include photo galleries for each project
- FR1.3.4: Embed videos related to projects
- FR1.3.5: Display success stories and testimonials
- FR1.3.6: Show project-specific impact metrics
- FR1.3.7: Enable project-specific donations
- FR1.3.8: Link to volunteer opportunities per project

#### 3.1.4 Blog/News Section
**Description**: Platform for sharing updates and stories

**Functional Requirements**:
- FR1.4.1: Display blog posts in paginated list
- FR1.4.2: Provide category-based filtering
- FR1.4.3: Enable full-text search
- FR1.4.4: Show individual article pages with rich media
- FR1.4.5: Include social media sharing buttons
- FR1.4.6: Display related articles
- FR1.4.7: Show article metadata (date, author, category)

#### 3.1.5 Contact Page
**Description**: Multiple ways for visitors to connect with the organization

**Functional Requirements**:
- FR1.5.1: Provide contact form with subject categories
- FR1.5.2: Implement spam protection (reCAPTCHA)
- FR1.5.3: Display office locations on interactive map
- FR1.5.4: List phone numbers and email addresses
- FR1.5.5: Show office hours
- FR1.5.6: Send auto-acknowledgment emails

### 3.2 Donation System

#### 3.2.1 Donation Processing
**Description**: Comprehensive donation management system

**Functional Requirements**:
- FR2.1.1: Accept one-time donations
- FR2.1.2: Enable recurring donations (monthly, quarterly, annually)
- FR2.1.3: Allow donors to designate funds to specific projects
- FR2.1.4: Provide suggested donation amounts
- FR2.1.5: Accept custom donation amounts
- FR2.1.6: Include optional donor message/dedication field
- FR2.1.7: Generate automatic tax receipts
- FR2.1.8: Send confirmation emails

#### 3.2.2 Payment Methods
**Description**: Multiple payment gateway integrations

**Functional Requirements**:
- FR2.2.1: M-Pesa integration (STK Push and Paybill)
- FR2.2.2: Credit/Debit card processing (Visa, MasterCard)
- FR2.2.3: PayPal integration
- FR2.2.4: Display bank transfer details
- FR2.2.5: Secure payment processing (SSL, PCI compliance)
- FR2.2.6: Handle payment failures gracefully
- FR2.2.7: Process refunds when necessary

### 3.3 Career/Volunteer Portal

#### 3.3.1 Opportunity Listings
**Description**: Platform for posting and managing opportunities

**Functional Requirements**:
- FR3.1.1: Display job listings with details
- FR3.1.2: Show volunteer opportunities
- FR3.1.3: Filter by location, type, and requirements
- FR3.1.4: Search functionality
- FR3.1.5: Application deadline display
- FR3.1.6: Share opportunities on social media

#### 3.3.2 Application System
**Description**: Online application submission and tracking

**Functional Requirements**:
- FR3.2.1: Online application form
- FR3.2.2: CV/Resume upload (PDF, DOC, DOCX)
- FR3.2.3: Cover letter submission
- FR3.2.4: Multiple document uploads
- FR3.2.5: Save draft applications
- FR3.2.6: Application status tracking
- FR3.2.7: Email notifications for status changes

### 3.4 User Account Features

#### 3.4.1 Donor Portal
**Description**: Personalized dashboard for registered donors

**Functional Requirements**:
- FR4.1.1: User registration and login
- FR4.1.2: View donation history
- FR4.1.3: Download tax receipts
- FR4.1.4: Manage recurring donations
- FR4.1.5: Update payment methods
- FR4.1.6: Set communication preferences
- FR4.1.7: View impact of donations

#### 3.4.2 Applicant Portal
**Description**: Dashboard for job/volunteer applicants

**Functional Requirements**:
- FR4.2.1: View submitted applications
- FR4.2.2: Track application status
- FR4.2.3: Update profile information
- FR4.2.4: Upload additional documents
- FR4.2.5: Receive messages from HR
- FR4.2.6: Withdraw applications

### 3.5 Administrative Features

#### 3.5.1 Content Management System
**Description**: Tools for managing website content

**Functional Requirements**:
- FR5.1.1: WYSIWYG editor for pages
- FR5.1.2: Media library management
- FR5.1.3: Blog post creation and editing
- FR5.1.4: Project information updates
- FR5.1.5: Team member management
- FR5.1.6: Impact metrics updating
- FR5.1.7: Menu and navigation management
- FR5.1.8: SEO metadata management

#### 3.5.2 Donation Management
**Description**: Tools for managing donations and donors

**Functional Requirements**:
- FR5.2.1: View all donations with filters
- FR5.2.2: Generate donation reports
- FR5.2.3: Export donor data
- FR5.2.4: Send bulk thank you emails
- FR5.2.5: Manage recurring donation schedules
- FR5.2.6: Process refunds
- FR5.2.7: Generate financial reports

#### 3.5.3 HR Management
**Description**: Tools for managing recruitment

**Functional Requirements**:
- FR5.3.1: Create and edit job/volunteer listings
- FR5.3.2: Review submitted applications
- FR5.3.3: Rate and comment on applications
- FR5.3.4: Update application statuses
- FR5.3.5: Send bulk emails to applicants
- FR5.3.6: Export applicant data
- FR5.3.7: Archive old listings

#### 3.5.4 Analytics Dashboard
**Description**: Comprehensive analytics and reporting

**Functional Requirements**:
- FR5.4.1: Website traffic analytics
- FR5.4.2: Donation analytics and trends
- FR5.4.3: Content performance metrics
- FR5.4.4: User engagement tracking
- FR5.4.5: Custom report generation
- FR5.4.6: Data export capabilities
- FR5.4.7: Real-time dashboard updates

#### 3.5.5 User Management
**Description**: System for managing all user accounts

**Functional Requirements**:
- FR5.5.1: Create admin accounts
- FR5.5.2: Role-based access control
- FR5.5.3: Activity logs
- FR5.5.4: Password reset functionality
- FR5.5.5: Two-factor authentication
- FR5.5.6: Session management

---

## 4. External Interface Requirements

### 4.1 User Interfaces
- Responsive web design for all screen sizes
- Intuitive navigation with clear menu structure
- Consistent branding throughout
- Accessibility features (screen reader support, keyboard navigation)
- Multi-language support (English and Swahili)

### 4.2 Hardware Interfaces
- No specific hardware requirements beyond standard web browsing devices

### 4.3 Software Interfaces

#### 4.3.1 Payment Gateway APIs
- M-Pesa Daraja API
- PayPal REST API
- Stripe or similar for card processing

#### 4.3.2 Communication APIs
- SendGrid or Mailgun for transactional emails
- Twilio or Africa's Talking for SMS notifications

#### 4.3.3 Third-party Integrations
- Google Analytics
- Google Maps API
- Social media APIs (Facebook, Twitter, LinkedIn)
- reCAPTCHA for spam protection

### 4.4 Communications Interfaces
- HTTPS protocol for all communications
- RESTful API for frontend-backend communication
- WebSocket for real-time features (if needed)

---

## 5. Non-Functional Requirements

### 5.1 Performance Requirements
- NFR1: Page load time < 3 seconds on 3G connection
- NFR2: Support minimum 1000 concurrent users
- NFR3: Database queries optimized for < 100ms response time
- NFR4: Image optimization and lazy loading
- NFR5: CDN usage for static assets

### 5.2 Safety Requirements
- NFR6: Regular automated backups (daily)
- NFR7: Disaster recovery plan
- NFR8: Data redundancy

### 5.3 Security Requirements
- NFR9: SSL certificate for entire site
- NFR10: PCI DSS compliance for payment processing
- NFR11: OWASP security best practices
- NFR12: Regular security audits
- NFR13: Encrypted storage of sensitive data
- NFR14: Secure API endpoints with authentication

### 5.4 Software Quality Attributes

#### 5.4.1 Availability
- NFR15: 99.9% uptime SLA
- NFR16: Graceful degradation during high load
- NFR17: Proper error handling and user feedback

#### 5.4.2 Usability
- NFR18: Intuitive interface requiring no training
- NFR19: Mobile-first responsive design
- NFR20: WCAG 2.1 AA compliance
- NFR21: Support for major browsers (2 latest versions)

#### 5.4.3 Maintainability
- NFR22: Modular code architecture
- NFR23: Comprehensive documentation
- NFR24: Version control (Git)
- NFR25: Automated testing suite

#### 5.4.4 Scalability
- NFR26: Horizontal scaling capability
- NFR27: Database optimization for growth
- NFR28: Caching mechanisms
- NFR29: Queue system for heavy operations

---

## 6. Other Requirements

### 6.1 Legal and Regulatory Requirements
- Compliance with Kenyan Data Protection Act
- NGO regulatory compliance
- Tax receipt requirements for donations
- Terms of Service and Privacy Policy

### 6.2 Operational Requirements
- Staff training materials
- User documentation
- System administration guide
- Deployment procedures

### 6.3 Future Enhancements (Out of current scope)
- Mobile applications (iOS/Android)
- Advanced donor CRM features
- Event management system
- E-learning platform for farmers
- Automated impact reporting

---

## Appendices

### Appendix A: Glossary
- **Ustawi Wa Jamii**: Swahili for "Community Development"
- **Farmer Field School (FFS)**: Participatory training methodology
- **County Project Coordinator (CPC)**: Regional representative

### Appendix B: Use Case Diagrams
[To be added during design phase]

### Appendix C: Data Model
[To be added during design phase]

---

**Document Approval**

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Manager | | | |
| Technical Lead | | | |
| Client Representative | | | |